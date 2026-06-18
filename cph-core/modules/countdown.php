<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// ── Aktivacija: kreiraj tablicu ───────────────────────────────────────────────

function cph_countdown_activate() {
    global $wpdb;
    $table   = $wpdb->prefix . 'cph_timers';
    $charset = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id              INT UNSIGNED  NOT NULL AUTO_INCREMENT,
        film_name       VARCHAR(255)  NOT NULL,
        release_date    DATE          NOT NULL,
        subtext         VARCHAR(255)  NOT NULL DEFAULT '',
        after_text      VARCHAR(255)  NOT NULL DEFAULT 'Now in cinemas!',
        hide_after_days INT UNSIGNED  NOT NULL DEFAULT 180,
        accent_color    VARCHAR(20)   NOT NULL DEFAULT '#e835ac',
        shortcode_slug  VARCHAR(100)  NOT NULL,
        created_at      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
}

// ── JS za admin (footer) ──────────────────────────────────────────────────────

function cph_countdown_admin_js( $hook ) {
    if ( ! in_array( $hook, ['toplevel_page_cph-countdown', 'coloring-photos_page_cph-countdown'], true ) ) return;
    ?>
    <script>
    function cphIsHex(v){ return /^#[0-9a-fA-F]{6}$/.test(v); }
    function cphTint(hex,alpha){
        hex=(hex||'#e835ac').replace('#','');
        if(hex.length===3)hex=hex.split('').map(function(c){return c+c;}).join('');
        var n=parseInt(hex,16);
        if(isNaN(n))return 'rgba(232,53,172,'+alpha+')';
        return 'rgba('+((n>>16)&255)+','+((n>>8)&255)+','+(n&255)+','+alpha+')';
    }
    function cphPreview(pfx){
        pfx=pfx||'';
        var colorEl=document.getElementById(pfx+'accent_color');
        if(!colorEl)return;
        var color=colorEl.value||'#e835ac';
        var tint=cphTint(color,0.10);
        var wrap=document.getElementById(pfx+'preview_wrap');
        if(!wrap)return;
        wrap.querySelectorAll('.cph-preview-unit').forEach(function(u){u.style.background=tint;});
        wrap.querySelectorAll('.cph-preview-num,.cph-preview-sep,.cph-preview-lbl').forEach(function(el){el.style.color=color;});
        var subEl=document.getElementById(pfx+'subtext');
        var subOut=wrap.querySelector('.cph-preview-sub');
        if(subEl&&subOut)subOut.textContent=subEl.value||'';
    }
    function cphBindColor(pfx){
        pfx=pfx||'';
        var picker=document.getElementById(pfx+'accent_color');
        var hexIn=document.getElementById(pfx+'accent_hex');
        if(!picker||!hexIn)return;
        picker.addEventListener('input',function(){hexIn.value=this.value;cphPreview(pfx);});
        hexIn.addEventListener('input',function(){if(cphIsHex(this.value)){picker.value=this.value;cphPreview(pfx);}});
    }
    function cphBindForm(pfx){
        pfx=pfx||'';
        ['accent_color','subtext'].forEach(function(id){
            var el=document.getElementById(pfx+id);
            if(el)el.addEventListener('input',function(){cphPreview(pfx);});
        });
        cphBindColor(pfx);
        cphPreview(pfx);
    }
    function cphOpenEdit(data){
        var modal=document.getElementById('cph-edit-modal');
        if(!modal)return;
        ['id','film_name','release_date','subtext','after_text','hide_after_days','accent_color','shortcode_slug'].forEach(function(f){
            var el=document.getElementById('edit_'+f);
            if(el)el.value=data[f]||'';
        });
        var hexEl=document.getElementById('edit_accent_hex');
        if(hexEl)hexEl.value=data.accent_color||'#e835ac';
        modal.classList.add('open');
        setTimeout(function(){cphPreview('edit_');},30);
    }
    function cphCloseEdit(){
        var m=document.getElementById('cph-edit-modal');
        if(m)m.classList.remove('open');
    }
    function cphCopy(el,text){
        navigator.clipboard.writeText(text).then(function(){
            var orig=el.textContent;
            el.textContent='✓ Kopirano!';
            el.style.background='#e7f8ee';
            setTimeout(function(){el.textContent=orig;el.style.background='';},1600);
        });
    }
    document.addEventListener('DOMContentLoaded',function(){
        cphBindForm('');
        cphBindForm('edit_');
        var overlay=document.getElementById('cph-edit-modal');
        if(overlay)overlay.addEventListener('click',function(e){if(e.target===overlay)cphCloseEdit();});
    });
    </script>
    <?php
}
add_action( 'admin_footer', 'cph_countdown_admin_js' );

// ── Helper: form fields ───────────────────────────────────────────────────────

function cph_countdown_form_fields( $pfx='', $d=[] ) {
    $d = array_merge([
        'film_name'=>'','release_date'=>'','subtext'=>'',
        'after_text'=>'Now in cinemas!','hide_after_days'=>180,'accent_color'=>'#e835ac',
    ], $d);
    $p = esc_attr($pfx);
    $c = esc_attr($d['accent_color']);
    ?>
    <div class="cph-field-group">
        <label class="cph-label">Naziv filma *</label>
        <input class="cph-input" type="text" id="<?php echo $p;?>film_name" name="<?php echo $p;?>film_name"
               value="<?php echo esc_attr($d['film_name']); ?>" placeholder="npr. Forgotten Island" required>
    </div>
    <div class="cph-row-3">
        <div class="cph-field-group">
            <label class="cph-label">Datum izlaska *</label>
            <input class="cph-input" type="date" id="<?php echo $p;?>release_date" name="<?php echo $p;?>release_date"
                   value="<?php echo esc_attr($d['release_date']); ?>" required>
        </div>
        <div class="cph-field-group">
            <label class="cph-label">Boja timera</label>
            <div class="cph-color-row">
                <div class="cph-color-swatch">
                    <input type="color" id="<?php echo $p;?>accent_color" name="<?php echo $p;?>accent_color" value="<?php echo $c; ?>">
                </div>
                <input type="text" class="cph-input cph-color-hex" id="<?php echo $p;?>accent_hex"
                       value="<?php echo $c; ?>" placeholder="#e835ac" maxlength="7">
            </div>
        </div>
        <div class="cph-field-group">
            <label class="cph-label">Sakrij (dana)</label>
            <input class="cph-input" type="number" id="<?php echo $p;?>hide_after_days" name="<?php echo $p;?>hide_after_days"
                   value="<?php echo esc_attr($d['hide_after_days']); ?>" min="0" max="3650">
        </div>
    </div>
    <div class="cph-divider"></div>
    <div class="cph-field-group">
        <label class="cph-label">Tekst ispod timera</label>
        <input class="cph-input" type="text" id="<?php echo $p;?>subtext" name="<?php echo $p;?>subtext"
               value="<?php echo esc_attr($d['subtext']); ?>" placeholder="Forgotten Island — coming September 25, 2026">
    </div>
    <div class="cph-field-group">
        <label class="cph-label">Tekst nakon izlaska</label>
        <input class="cph-input" type="text" id="<?php echo $p;?>after_text" name="<?php echo $p;?>after_text"
               value="<?php echo esc_attr($d['after_text']); ?>" placeholder="Now in cinemas!">
    </div>
    <?php
}

// ── Helper: preview widget ────────────────────────────────────────────────────

function cph_countdown_preview( $pfx='', $d=[] ) {
    $d   = array_merge(['accent_color'=>'#e835ac','subtext'=>''], $d);
    $c   = esc_attr($d['accent_color']);
    $pfx = esc_attr($pfx);
    ?>
    <div class="cph-preview-card">
        <div class="cph-preview-label">Pregled — kako izgleda na webu</div>
        <div id="<?php echo $pfx;?>preview_wrap">
            <div class="cph-preview-units">
                <?php foreach([['102','DAYS'],['14','HRS'],['37','MIN'],['22','SEC']] as $i=>$u):
                    if($i>0) echo '<span class="cph-preview-sep" style="color:'.esc_attr($c).';">:</span>';
                ?>
                <div class="cph-preview-unit" style="background:<?php echo $c;?>1a;">
                    <span class="cph-preview-num" style="color:<?php echo $c;?>"><?php echo $u[0];?></span>
                    <span class="cph-preview-lbl" style="color:<?php echo $c;?>"><?php echo $u[1];?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="cph-preview-sub"><?php echo esc_html($d['subtext']);?></div>
        </div>
    </div>
    <?php
}

// ── Admin stranica ────────────────────────────────────────────────────────────

function cph_countdown_page() {
    global $wpdb;
    $table  = $wpdb->prefix . 'cph_timers';
    $notice = null;

    if ( isset($_GET['delete']) && check_admin_referer('cph_delete_'.intval($_GET['delete'])) ) {
        $wpdb->delete($table, ['id'=>intval($_GET['delete'])]);
        $notice = ['type'=>'success','msg'=>'Timer obrisan.'];
    }
    if ( isset($_POST['cph_add']) && check_admin_referer('cph_add_timer') ) {
        $film = sanitize_text_field($_POST['film_name']??'');
        $date = sanitize_text_field($_POST['release_date']??'');
        if ($film && $date) {
            $slug   = sanitize_title($film);
            $exists = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE shortcode_slug=%s",$slug));
            if ($exists) $slug .= '-'.time();
            $wpdb->insert($table,[
                'film_name'       => $film,
                'release_date'    => $date,
                'subtext'         => sanitize_text_field($_POST['subtext']??''),
                'after_text'      => sanitize_text_field($_POST['after_text']??'Now in cinemas!'),
                'hide_after_days' => absint($_POST['hide_after_days']??180),
                'accent_color'    => sanitize_hex_color($_POST['accent_color']??'#e835ac'),
                'shortcode_slug'  => $slug,
            ]);
            $notice = ['type'=>'success','msg'=>'Timer dodan! Shortcode: <code>[countdown id="'.esc_html($slug).'"]</code>'];
        } else {
            $notice = ['type'=>'error','msg'=>'Naziv filma i datum su obavezni.'];
        }
    }
    if ( isset($_POST['cph_edit']) && check_admin_referer('cph_edit_timer') ) {
        $id = intval($_POST['edit_id']??0);
        if ($id) {
            $wpdb->update($table,[
                'film_name'       => sanitize_text_field($_POST['edit_film_name']??''),
                'release_date'    => sanitize_text_field($_POST['edit_release_date']??''),
                'subtext'         => sanitize_text_field($_POST['edit_subtext']??''),
                'after_text'      => sanitize_text_field($_POST['edit_after_text']??'Now in cinemas!'),
                'hide_after_days' => absint($_POST['edit_hide_after_days']??180),
                'accent_color'    => sanitize_hex_color($_POST['edit_accent_color']??'#e835ac'),
            ],['id'=>$id]);
            $notice = ['type'=>'success','msg'=>'Timer ažuriran.'];
        }
    }

    $timers = $wpdb->get_results("SELECT * FROM $table ORDER BY release_date ASC");
    $today  = new DateTime('today');
    ?>
    <div id="cph-app">
        <h1 class="cph-page-title">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Countdown Timeri
        </h1>

        <?php if ($notice): ?>
        <div class="cph-notice cph-notice-<?php echo esc_attr($notice['type']);?>">
            <?php echo $notice['type']==='success'?'✓':'✕'; ?> <?php echo $notice['msg']; ?>
        </div>
        <?php endif; ?>

        <div class="cph-layout">
            <div class="cph-card">
                <div class="cph-card-title"><div class="cph-card-title-icon">＋</div>Dodaj novi timer</div>
                <form method="post">
                    <?php wp_nonce_field('cph_add_timer'); ?>
                    <?php cph_countdown_form_fields('', []); ?>
                    <div class="cph-btn-row">
                        <button type="submit" name="cph_add" class="cph-btn cph-btn-primary">Dodaj timer</button>
                    </div>
                </form>
            </div>
            <div><?php cph_countdown_preview('', []); ?></div>
        </div>

        <?php if (!empty($timers)): ?>
        <div class="cph-table-card">
            <div class="cph-table-header">
                <div class="cph-table-title">Svi timeri <span class="cph-count-badge"><?php echo count($timers); ?></span></div>
            </div>
            <table class="cph-table">
                <thead><tr>
                    <th>Film</th><th>Datum izlaska</th><th>Status</th>
                    <th>Boja</th><th>Shortcode</th><th>Akcije</th>
                </tr></thead>
                <tbody>
                <?php foreach ($timers as $t):
                    $release   = new DateTime($t->release_date);
                    $diff      = $today->diff($release);
                    $days_left = (int)$diff->format('%r%a');
                    $hide_date = (clone $release)->modify('+'.$t->hide_after_days.' days');
                    $is_done   = $today >= $release;
                    $is_hidden = $today > $hide_date;
                    $color     = esc_attr($t->accent_color ?: '#e835ac');
                    $sc        = '[countdown id="'.esc_attr($t->shortcode_slug).'"]';
                    $del_url   = wp_nonce_url(admin_url('admin.php?page=cph-countdown&delete='.$t->id),'cph_delete_'.$t->id);
                    $ed_data   = wp_json_encode(['id'=>(int)$t->id,'film_name'=>$t->film_name,'release_date'=>$t->release_date,'subtext'=>$t->subtext,'after_text'=>$t->after_text,'hide_after_days'=>(int)$t->hide_after_days,'accent_color'=>$t->accent_color,'shortcode_slug'=>$t->shortcode_slug]);
                    if (!$is_done)      $badge='<span class="cph-status cph-status-active"><span class="cph-dot" style="background:'.$color.'"></span>Za '.$days_left.' dana</span>';
                    elseif($is_hidden)  $badge='<span class="cph-status cph-status-expired">Skriveno</span>';
                    else                $badge='<span class="cph-status cph-status-expired">U kinima</span>';
                ?>
                <tr>
                    <td class="cph-film-name"><?php echo esc_html($t->film_name); ?></td>
                    <td><?php echo esc_html(date_i18n('d.m.Y.', strtotime($t->release_date))); ?></td>
                    <td><?php echo $badge; ?></td>
                    <td><span style="display:inline-flex;align-items:center;gap:6px;"><span style="width:14px;height:14px;border-radius:4px;background:<?php echo $color;?>;display:inline-block;border:1px solid rgba(0,0,0,.1);"></span><code style="font-size:11px;color:#6b6b80;"><?php echo $color; ?></code></span></td>
                    <td><span class="cph-sc" onclick="cphCopy(this,'<?php echo esc_js($sc); ?>')"><?php echo esc_html($sc); ?></span></td>
                    <td>
                        <button class="cph-action-btn cph-edit-btn" type="button" onclick='cphOpenEdit(<?php echo $ed_data; ?>)'>Uredi</button>
                        <a href="<?php echo esc_url($del_url); ?>" class="cph-action-btn cph-del-btn" onclick="return confirm('Obriši timer za <?php echo esc_js($t->film_name); ?>?')">Obriši</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <div class="cph-modal-overlay" id="cph-edit-modal">
        <div class="cph-modal-box">
            <div class="cph-modal-top">
                <h2>Uredi timer</h2>
                <button class="cph-modal-close" onclick="cphCloseEdit()" type="button">✕</button>
            </div>
            <form method="post">
                <?php wp_nonce_field('cph_edit_timer'); ?>
                <input type="hidden" id="edit_id" name="edit_id">
                <input type="hidden" id="edit_shortcode_slug" name="edit_shortcode_slug">
                <div class="cph-modal-body">
                    <div class="cph-layout">
                        <div><?php cph_countdown_form_fields('edit_', []); ?></div>
                        <div><?php cph_countdown_preview('edit_', []); ?></div>
                    </div>
                </div>
                <div class="cph-modal-footer">
                    <button type="submit" name="cph_edit" class="cph-btn cph-btn-primary">Spremi izmjene</button>
                    <button type="button" class="cph-btn cph-btn-ghost" onclick="cphCloseEdit()">Odustani</button>
                </div>
            </form>
        </div>
    </div>
    <?php
}

// ── Shortcode (frontend) ──────────────────────────────────────────────────────

function cph_countdown_shortcode( $atts ) {
    global $wpdb;
    $table = $wpdb->prefix . 'cph_timers';
    $atts  = shortcode_atts(['id'=>''], $atts);
    if (!$atts['id']) return '';
    $timer = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE shortcode_slug=%s LIMIT 1", sanitize_title($atts['id'])));
    if (!$timer) return '';
    $today     = new DateTime('today');
    $release   = new DateTime($timer->release_date);
    $hide_date = (clone $release)->modify('+'.$timer->hide_after_days.' days');
    if ($today > $hide_date) return '';
    $color = esc_attr($timer->accent_color ?: '#e835ac');
    if ($today >= $release) {
        return '<p style="text-align:center;font-weight:700;font-size:1.1em;color:'.$color.';margin:16px 0;">'.esc_html($timer->after_text).'</p>';
    }
    $uid   = 'cph_'.esc_attr($timer->id);
    $sub   = $timer->subtext ? '<p style="font-size:15px;color:#666;margin-top:12px;text-align:center;font-style:italic;">'.esc_html($timer->subtext).'</p>' : '';
    $units = [['d','DAYS'],['h','HRS'],['m','MIN'],['s','SEC']];
    ob_start(); ?>
    <div id="<?php echo $uid;?>" style="text-align:center;margin:20px 0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">
        <div style="display:inline-flex;gap:6px;align-items:center;">
            <?php foreach($units as $i=>$u):
                if($i>0) echo '<span style="font-size:22px;font-weight:700;color:'.$color.';margin-bottom:8px;">:</span>';
            ?>
            <div style="display:flex;flex-direction:column;align-items:center;background:<?php echo $color;?>1a;border-radius:10px;padding:12px 14px;min-width:64px;">
                <span id="<?php echo $uid.'_'.$u[0];?>" style="font-size:28px;font-weight:700;line-height:1;color:<?php echo $color;?>;">--</span>
                <span style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1.4px;margin-top:5px;color:<?php echo $color;?>;"><?php echo $u[1];?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php echo $sub; ?>
    </div>
    <script>(function(){
        var t=new Date("<?php echo esc_js($timer->release_date);?>T00:00:00");
        var id="<?php echo esc_js($uid);?>";
        var af=<?php echo wp_json_encode($timer->after_text);?>;
        var cl=<?php echo wp_json_encode($color);?>;
        function pad(n){return n<10?'0'+n:''+n;}
        function tick(){
            var now=new Date(),diff=t-now,el;
            if(diff<=0){var w=document.getElementById(id);if(w)w.innerHTML='<p style="text-align:center;font-weight:700;font-size:1.1em;color:'+cl+';margin:16px 0;">'+af+'</p>';return;}
            var d=Math.floor(diff/86400000),h=Math.floor((diff%86400000)/3600000),m=Math.floor((diff%3600000)/60000),s=Math.floor((diff%60000)/1000);
            if((el=document.getElementById(id+'_d')))el.textContent=d;
            if((el=document.getElementById(id+'_h')))el.textContent=pad(h);
            if((el=document.getElementById(id+'_m')))el.textContent=pad(m);
            if((el=document.getElementById(id+'_s')))el.textContent=pad(s);
        }
        tick();setInterval(tick,1000);
    })();</script>
    <?php
    return ob_get_clean();
}
add_shortcode('countdown','cph_countdown_shortcode');
