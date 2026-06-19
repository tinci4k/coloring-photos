<?php
/**
 * Raise upload size limit to 6GB for All-in-One WP Migration imports.
 */
add_filter( 'upload_size_limit', function() {
	return 6 * 1024 * 1024 * 1024; // 6GB in bytes
} );
