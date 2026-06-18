#!/usr/bin/env python3
"""
Coloring Page Pipeline
======================
Za svaki batch slika:
1. Formatira sliku na 1240x1754 (portrait) ili 1754x1240 (landscape) s bijelom pozadinom
2. Dodaje find-more-coloring-photos.png logo u donji desni kut
3. Sprema output kao optimizirani PNG (crno-bijele bojanke)
4. Šalje downscale kopiju Claude API-ju koji vraća naziv, opis, kategorije, tagove
5. Preskače slike koje su već obrađene (fallback pri padu skripte)
6. Generira WooCommerce import CSV

Struktura foldera:
  coloring_pipeline.py              ← ova skripta
  find-more-coloring-photos.png     ← logo (isti folder)
  nove_slike/                       ← ulazne slike
  output/                           ← generirane slike + import.csv
  .env                              ← ANTHROPIC_API_KEY=sk-ant-...

Postavljanje:
  pip install pillow anthropic python-dotenv

Pokretanje (API key iz .env fajla):
  python coloring_pipeline.py \
    --category "Animated Movies > How to Train Your Dragon"

Pokretanje s eksplicitnim API keyem:
  python coloring_pipeline.py \
    --api-key sk-ant-... \
    --category "Animated Movies > How to Train Your Dragon"

Pokretanje s kontekstom za AI:
  python coloring_pipeline.py \
    --category "Animated Movies > How to Train Your Dragon" \
    --context "Novi film 2025, vikingski likovi: Hiccup, Astrid, Toothless"
"""

import argparse
import base64
import csv
import io
import json
import os
import sys
from pathlib import Path

try:
    from PIL import Image
except ImportError:
    print("ERROR: Pillow nije instaliran. Pokreni: pip install pillow")
    sys.exit(1)

try:
    import anthropic
except ImportError:
    print("ERROR: Anthropic SDK nije instaliran. Pokreni: pip install anthropic")
    sys.exit(1)

# python-dotenv je opcionalan — bez njega samo čita env varijable
try:
    from dotenv import load_dotenv
    load_dotenv()
except ImportError:
    pass


# ── Konstante ────────────────────────────────────────────────────────────────

# Izlazna rezolucija
PORTRAIT_SIZE  = (1240, 1754)
LANDSCAPE_SIZE = (1754, 1240)

# Margina oko crteža na platnu (px)
MARGIN = 60

# Logo – maksimalna visina u pikselima (proporcionalno se skalira)
LOGO_MAX_HEIGHT = 80

# Razmak loga od desnog i donjeg ruba (px)
LOGO_PADDING = 30

# Maksimalna širina slike koja se šalje API-ju (downscale samo za analizu)
API_MAX_WIDTH = 800

# Fiksne putanje
SCRIPT_DIR  = Path(__file__).parent
LOGO_PATH   = SCRIPT_DIR / "find-more-coloring-photos.png"
INPUT_DIR   = SCRIPT_DIR / "nove_slike"
OUTPUT_DIR  = SCRIPT_DIR / "output"
BASE_URL    = "https://coloring.photos/wp-content/uploads/"
CSV_PATH    = OUTPUT_DIR / "import.csv"

SUPPORTED_EXTENSIONS = {".jpg", ".jpeg", ".png", ".webp", ".bmp", ".tiff"}


# ── Image processing ─────────────────────────────────────────────────────────

def format_image(src_path: Path, logo: Image.Image, output_dir: Path) -> Path:
    """
    Otvori sliku, stavi je na canvas, dodaj logo, optimiziraj za web.
    Output: PNG s PNG optimizacijom (optimize=True, za crno-bijele slike ~50-150KB).
    """
    img = Image.open(src_path).convert("RGBA")
    w, h = img.size

    canvas_size = PORTRAIT_SIZE if h >= w else LANDSCAPE_SIZE
    canvas_w, canvas_h = canvas_size

    available_w = canvas_w - 2 * MARGIN
    available_h = canvas_h - 2 * MARGIN

    img_ratio  = w / h
    area_ratio = available_w / available_h

    if img_ratio > area_ratio:
        new_w = available_w
        new_h = int(new_w / img_ratio)
    else:
        new_h = available_h
        new_w = int(new_h * img_ratio)

    img_resized = img.resize((new_w, new_h), Image.LANCZOS)

    canvas = Image.new("RGBA", canvas_size, (255, 255, 255, 255))

    paste_x = (canvas_w - new_w) // 2
    paste_y = (canvas_h - new_h) // 2
    canvas.paste(img_resized, (paste_x, paste_y), img_resized)

    if logo is not None:
        logo_w, logo_h = logo.size
        if logo_h > LOGO_MAX_HEIGHT:
            scale   = LOGO_MAX_HEIGHT / logo_h
            logo_w  = int(logo_w * scale)
            logo_h  = LOGO_MAX_HEIGHT
            logo_scaled = logo.resize((logo_w, logo_h), Image.LANCZOS)
        else:
            logo_scaled = logo

        lx = canvas_w - logo_scaled.width  - LOGO_PADDING
        ly = canvas_h - logo_scaled.height - LOGO_PADDING
        canvas.paste(logo_scaled, (lx, ly), logo_scaled)

    # Konvertiraj u RGB (JPEG ne podržava L mode direktno kroz sve Pillow verzije)
    final = canvas.convert("RGB")

    output_path = output_dir / (src_path.stem + ".jpg")
    final.save(output_path, "JPEG", quality=30, optimize=True)
    return output_path


def make_api_image(image_path: Path) -> tuple[str, str]:
    """
    Napravi downscale kopiju slike za API poziv (u memoriji, ne sprema na disk).
    Vraća (base64_string, media_type).
    """
    img = Image.open(image_path).convert("RGB")
    w, h = img.size

    if w > API_MAX_WIDTH:
        scale = API_MAX_WIDTH / w
        img = img.resize((API_MAX_WIDTH, int(h * scale)), Image.LANCZOS)

    buffer = io.BytesIO()
    img.save(buffer, format="JPEG", quality=85)
    buffer.seek(0)
    return base64.standard_b64encode(buffer.read()).decode("utf-8"), "image/jpeg"


# ── Fallback: provjera već obrađenih slika ───────────────────────────────────

def load_existing_csv(csv_path: Path) -> dict[str, dict]:
    """
    Učitaj postojeći CSV i vrati rječnik {filename: row}.
    Koristi se da preskočimo već obrađene slike ako skripta pukne na pola.
    """
    existing = {}
    if not csv_path.exists():
        return existing
    with open(csv_path, newline="", encoding="utf-8") as f:
        reader = csv.DictReader(f)
        for row in reader:
            # Izvuci filename iz Images URL-a
            url = row.get("Images", "")
            filename = url.split("/")[-1]
            if filename:
                existing[filename] = row
    return existing


def already_processed(src_stem: str, output_dir: Path, existing_csv: dict) -> tuple[bool, Path | None]:
    """
    Provjeri je li slika već obrađena.
    Vraća (True/False, path_if_found).
    """
    for ext in ("*.jpg", "*.jpeg", "*.png"):
        for f in output_dir.glob(ext):
            if f.stem == src_stem or src_stem in f.stem:
                if f.name in existing_csv:
                    return True, f
    return False, None


# ── AI analiza ───────────────────────────────────────────────────────────────

SYSTEM_PROMPT = """Ti si SEO ekspert za dječje bojanke (coloring pages).
Za svaku sliku którú dobiješ vrati ISKLJUČIVO validan JSON bez ikakvog dodatnog teksta,
bez markdown backtick oznaka, bez objašnjenja.

JSON format koji moraš vratiti:
{
  "file_slug": "naziv-slike-coloring-page",
  "display_name": "Naziv za prikaz na webu",
  "short_description": "SEO opis u 3-5 rečenica na engleskom.",
  "alt_text": "Kratki opisni alt tekst za sliku, max 125 znakova, na engleskom.",
  "tags": ["tag1", "tag2", "tag3"]
}

Pravila:
- file_slug: samo mala slova, crtice umjesto razmaka, uvijek završava s -coloring-page
- display_name: kratko, čitljivo, na engleskom, BEZ riječi "Coloring Page" na kraju,
  npr. "Hiccup" ili "Nero in Venice", ne "Hiccup Coloring Page"
- short_description: 3-5 rečenica, prirodan informativan opis koji opisuje lik/scenu,
  za koga je bojanka, što dijete može naučiti/vidjeti na njoj. Na engleskom.
  Nemoj koristiti fraze poput "perfect for" više od jednom.
- alt_text: opisuje što je na slici, uključuje naziv lika i filma, max 125 znakova,
  završava s "coloring page", npr. "Hiccup holding a Viking helmet coloring page from How to Train Your Dragon"
- tags: točno 2-3 taga, usko vezana uz konkretni lik ili scenu na slici,
  bez općenitih tagova poput "animated movie", "coloring page", "kids" i sl.
  Svaki tag su jedna ili više riječi BEZ crtice između njih (npr. "black cat", ne "black-cat").
  Na engleskom.
"""

def analyse_image(image_path: Path, client: anthropic.Anthropic, batch_context: str) -> dict:
    """Pošalji downscale verziju slike API-ju i vrati parsani JSON."""

    image_data, media_type = make_api_image(image_path)

    user_content = []
    prompt_text = (
        f"Kontekst za ovaj batch bojanki: {batch_context}\n\nAnaliziraj sliku i vrati JSON."
        if batch_context else
        "Analiziraj sliku i vrati JSON."
    )
    user_content.append({"type": "text", "text": prompt_text})
    user_content.append({
        "type": "image",
        "source": {"type": "base64", "media_type": media_type, "data": image_data},
    })

    response = client.messages.create(
        model="claude-haiku-4-5-20251001",
        max_tokens=1024,
        system=SYSTEM_PROMPT,
        messages=[{"role": "user", "content": user_content}],
    )

    raw_text = response.content[0].text.strip()

    # Ukloni markdown code fence ako ga model doda (```json ... ```)
    if raw_text.startswith("```"):
        raw_text = raw_text.split("\n", 1)[-1]  # ukloni prvu liniju (```json)
        raw_text = raw_text.rsplit("```", 1)[0]  # ukloni završni ```
        raw_text = raw_text.strip()

    try:
        return json.loads(raw_text)
    except json.JSONDecodeError:
        print(f"  UPOZORENJE: Nije moguće parsati JSON za {image_path.name}")
        print(f"  Odgovor API-ja: {raw_text[:300]}")
        return {
            "file_slug": image_path.stem.lower() + "-coloring-page",
            "display_name": image_path.stem.replace("-", " ").title(),
            "short_description": "",
            "alt_text": "",
            "tags": [],
        }


# ── CSV export ───────────────────────────────────────────────────────────────

CSV_HEADERS = [
    "Type",
    "Name",
    "Short description",
    "Categories",
    "Tags",
    "Images",
    "Images Alt Text",
    "Orientation",
    "Download 1 name",
    "Download 1 URL",
]

def build_csv_row(meta: dict, image_url: str, category: str, orientation: str = "") -> dict:
    tags_str     = ", ".join(meta.get("tags", []))
    display_name = meta.get("display_name", "")
    return {
        "Type":             "downloadable",
        "Name":             display_name,
        "Short description": meta.get("short_description", ""),
        "Categories":       category,
        "Tags":             tags_str,
        "Images":           image_url,
        "Images Alt Text":  meta.get("alt_text", ""),
        "Orientation":      orientation,
        "Download 1 name":  f"{display_name} [www.coloring.photos]",
        "Download 1 URL":   image_url,
    }

def save_csv(rows: list[dict], csv_path: Path) -> None:
    with open(csv_path, "w", newline="", encoding="utf-8") as f:
        writer = csv.DictWriter(f, fieldnames=CSV_HEADERS, quoting=csv.QUOTE_ALL)
        writer.writeheader()
        writer.writerows(rows)


# ── Interaktivni unos ────────────────────────────────────────────────────────

def ask_category() -> str:
    """Traži kategoriju — jedan red, Enter za potvrdu."""
    print("Kategorija (npr. Animated Movies > How to Train Your Dragon):")
    print("  → ostavi prazno ako ne želiš kategoriju")
    category = input("> ").strip()
    return category

def ask_context() -> str:
    """
    Traži višeredni kontekst.
    Unos završava s dvostrukim Enterom (prazan red nakon teksta).
    """
    print("\nDodatni kontekst za AI (može biti višeredni tekst, zalijepi što želiš):")
    print("  → završi unos s dvostrukim Enterom (dva puta Enter na kraju)")
    print("  → ostavi prazno ako nema konteksta\n")

    lines = []
    while True:
        try:
            line = input()
        except EOFError:
            # Podrška za pipe/redirect unos
            break
        if line == "" and lines and lines[-1] == "":
            # Dva uzastopna prazna reda = kraj unosa
            break
        lines.append(line)

    # Ukloni trailing prazne redove
    while lines and lines[-1] == "":
        lines.pop()

    return "\n".join(lines).strip()


def ask_personal_note() -> str:
    """Traži osobnu napomenu o ovom batchu — opcionalno."""
    print("\nOsobna napomena o ovim bojankama (opcionalno):")
    print("  → npr. 'scene su detaljne, preporučam za djecu 6+, posebno mi se svidjela scena na otoku'")
    print("  → ostavi prazno za preskakanje\n")
    note = input("> ").strip()
    return note


# ── Generiranje tekstova za kategoriju ──────────────────────────────────────

CATEGORY_CONTENT_PROMPT = """You are writing SEO content for coloring.photos, a coloring page website run by a parent of three kids who are obsessed with coloring books.

The tone is: mostly professional but with a playful, slightly humorous, child-friendly warmth. Think "cool parent who takes coloring seriously but can still laugh about it." Avoid generic filler phrases like "perfect for all ages" or "hours of fun." Every sentence should feel like it was written by a human who actually knows this topic — not a content generator.

The site has coloring pages across many categories: Animals, Animated Movies, Anime, Cartoon Characters, Cartoons, Coloring pages for Adults, Dot to Dots, Educational, Fantasy, For Girls, Games, Holidays, Movies, Nature and Seasons, Netflix, Occasions, Series, Sports, Toys, Transport, Youtube, and more.

You will receive:
- Category name and path
- Context about the category/film/theme
- List of coloring page titles in this batch
- Optional personal note from the creator

Generate the following content in valid JSON (no markdown fences, no extra text):

{
  "intro_text": "2-3 sentences shown directly below the H1 heading. Engaging, specific to this category, with a touch of humor or warmth. Should make a parent or child want to scroll down.",
  "coloring_tips": "2-4 specific coloring suggestions for THIS category. Mention actual characters, scenes, or elements from the pages. Include color suggestions where relevant. Written like advice from someone who has actually colored these pages with their kids.",
  "difficulty_and_age": "One sentence about recommended age range and difficulty level. Be honest — if pages are detailed, say so. If they are simple, say so.",
  "footer_text": "2 paragraphs for the bottom of the page. First paragraph: more about the theme/film/topic with genuine enthusiasm. Second paragraph: encouragement to color and a subtle mention that all pages are free to print and download. No corporate-speak.",
  "faq": [
    {"question": "...", "answer": "..."},
    {"question": "...", "answer": "..."},
    {"question": "...", "answer": "..."},
    {"question": "...", "answer": "..."},
    {"question": "...", "answer": "..."}
  ]
}

FAQ rules:
- Minimum 5 questions, maximum 7
- Questions should be ones people actually search for — release dates, characters, streaming availability, "is it appropriate for kids", "how many pages", etc.
- Answers should be 2-4 sentences, informative and slightly conversational
- At least one FAQ should be specifically about the coloring pages themselves (difficulty, printing tips, etc.)
- Do NOT include generic questions like "Are these free?" as the only coloring-related question
"""

def generate_category_content(
    client: anthropic.Anthropic,
    category: str,
    context: str,
    personal_note: str,
    page_titles: list[str],
) -> dict:
    """Generiraj SEO tekstove za kategoriju na temelju svih podataka iz batcha."""

    titles_str = "\n".join(f"- {t}" for t in page_titles) if page_titles else "(no titles available)"

    user_msg = f"""Category: {category}

Context about this category/film/theme:
{context or 'No additional context provided.'}

Coloring pages in this batch:
{titles_str}

Personal note from creator:
{personal_note or 'None.'}

Generate the category content JSON now."""

    response = client.messages.create(
        model="claude-haiku-4-5-20251001",
        max_tokens=2048,
        system=CATEGORY_CONTENT_PROMPT,
        messages=[{"role": "user", "content": user_msg}],
    )

    raw = response.content[0].text.strip()
    if raw.startswith("```"):
        raw = raw.split("\n", 1)[-1]
        raw = raw.rsplit("```", 1)[0].strip()

    try:
        return json.loads(raw)
    except json.JSONDecodeError:
        print("  UPOZORENJE: Nije moguće parsati JSON za category content.")
        print(f"  Odgovor: {raw[:300]}")
        return {}


def save_category_content(content: dict, category: str, output_dir: Path) -> None:
    """Spremi generirane tekstove kao Markdown fajl."""
    if not content:
        return

    slug = category.replace(" > ", "-").replace(" ", "-").lower()
    slug = "".join(c for c in slug if c.isalnum() or c == "-")
    path = output_dir / f"category-content-{slug}.md"

    lines = [f"# Category Content: {category}\n"]

    if content.get("intro_text"):
        lines += ["## Intro Text (ispod H1)\n", content["intro_text"], ""]
    if content.get("coloring_tips"):
        lines += ["## Coloring Tips\n", content["coloring_tips"], ""]
    if content.get("difficulty_and_age"):
        lines += ["## Difficulty & Age\n", content["difficulty_and_age"], ""]
    if content.get("footer_text"):
        lines += ["## Footer Text (dno stranice)\n", content["footer_text"], ""]
    if content.get("faq"):
        lines.append("## FAQ\n")
        for item in content["faq"]:
            q = item.get("question", "")
            a = item.get("answer", "")
            if q and a:
                lines += [f"**Q: {q}**", f"A: {a}", ""]

    with open(path, "w", encoding="utf-8") as f:
        f.write("\n".join(lines))

    print(f"✓ Category content: {path}")


# ── Main ─────────────────────────────────────────────────────────────────────

def main():
    parser = argparse.ArgumentParser(description="Coloring page pipeline")
    parser.add_argument("--no-rename", action="store_true", help="Zadrži originalne nazive fajlova")
    args = parser.parse_args()

    api_key = os.environ.get("ANTHROPIC_API_KEY", "")
    if not api_key:
        print("ERROR: API key nije postavljen.")
        print("  Dodaj ANTHROPIC_API_KEY=sk-ant-... u .env fajl u istom folderu.")
        sys.exit(1)

    if not LOGO_PATH.exists():
        print(f"ERROR: Logo nije pronađen: {LOGO_PATH}")
        sys.exit(1)
    logo = Image.open(LOGO_PATH).convert("RGBA")

    if not INPUT_DIR.exists():
        print(f"ERROR: Folder 'nove_slike' nije pronađen: {INPUT_DIR}")
        sys.exit(1)

    images = sorted([
        p for p in INPUT_DIR.iterdir()
        if p.suffix.lower() in SUPPORTED_EXTENSIONS
    ])

    if not images:
        print(f"ERROR: Nema slika u folderu: {INPUT_DIR}")
        sys.exit(1)

    # ── Interaktivni unos ────────────────────────────────────────────────────
    print("=" * 50)
    print(f"  Pronađeno {len(images)} slika u nove_slike/")
    print("=" * 50)
    print()

    category      = ask_category()
    context       = ask_context()
    personal_note = ask_personal_note()

    print()
    print("─" * 50)
    print(f"  Kategorija     : {category or '(prazno)'}")
    print(f"  Kontekst       : {'da (' + str(len(context)) + ' znakova)' if context else '(prazno)'}")
    print(f"  Osobna napomena: {'da' if personal_note else '(prazno)'}")
    print("─" * 50)
    print()

    # ── Obrada ──────────────────────────────────────────────────────────────
    OUTPUT_DIR.mkdir(parents=True, exist_ok=True)

    existing_csv  = load_existing_csv(CSV_PATH)
    existing_rows = list(existing_csv.values())

    client = anthropic.Anthropic(api_key=api_key)

    new_rows    = []
    page_titles = []  # skupljamo naslove za category content
    skipped     = 0
    processed   = 0
    base_url    = BASE_URL.rstrip("/") + "/"

    for i, src_path in enumerate(images, 1):
        print(f"[{i}/{len(images)}] {src_path.name}")

        done, existing_path = already_processed(src_path.stem, OUTPUT_DIR, existing_csv)
        if done:
            print(f"  ↷ Preskočeno (već obrađeno: {existing_path.name})\n")
            skipped += 1
            continue

        # Detektiraj orijentaciju iz originalne slike prije procesiranja
        with Image.open(src_path) as orig:
            orig_w, orig_h = orig.size
        orientation = "portrait" if orig_h >= orig_w else "landscape"

        print("  → Formatiranje i optimizacija...")
        formatted_path = format_image(src_path, logo, OUTPUT_DIR)
        size_kb = formatted_path.stat().st_size // 1024
        print(f"  → Veličina: {size_kb} KB | Orijentacija: {orientation}")

        print("  → AI analiza...")
        meta = analyse_image(formatted_path, client, context)

        if not args.no_rename and meta.get("file_slug"):
            new_filename = meta["file_slug"] + ".jpg"
            new_path     = OUTPUT_DIR / new_filename
            if new_path.exists() and new_path != formatted_path:
                stem  = meta["file_slug"]
                count = 1
                while new_path.exists():
                    new_path = OUTPUT_DIR / f"{stem}-{count}.jpg"
                    count += 1
            formatted_path.rename(new_path)
            formatted_path = new_path
            print(f"  → Preimenovano u: {formatted_path.name}")

        image_url = base_url + formatted_path.name
        row = build_csv_row(meta, image_url, category, orientation)
        new_rows.append(row)

        if meta.get("display_name"):
            page_titles.append(meta["display_name"])

        print(f"  ✓ {meta.get('display_name', '?')}\n")
        processed += 1

        save_csv(existing_rows + new_rows, CSV_PATH)

    # ── Generiraj category content ───────────────────────────────────────────
    if category and (processed > 0 or skipped > 0):
        print("→ Generiranje category content tekstova...")
        all_titles = page_titles + [
            row.get("Name", "") for row in existing_rows if row.get("Name")
        ]
        content = generate_category_content(
            client, category, context, personal_note, all_titles
        )
        save_category_content(content, category, OUTPUT_DIR)

    print("=" * 50)
    print(f"✓ Obrađeno:   {processed} slika")
    if skipped:
        print(f"↷ Preskočeno: {skipped} slika (već postojale)")
    print(f"✓ CSV:        {CSV_PATH}")
    print(f"✓ Slike:      {OUTPUT_DIR}")
    print("=" * 50)


if __name__ == "__main__":
    main()
