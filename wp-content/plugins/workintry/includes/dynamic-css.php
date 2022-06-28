<?php
/**
 *
 * Dynamic CSS
 *
 * @package Workintry
 * @author    codesquare.co
 * @link      http://codesquare.co.com/
 * @since 1.0
 */
if( !function_exists( 'codesquare_workintry_print_default_color_css' ) ){
	function codesquare_workintry_print_default_color_css(){
		$color = codesquare_workintry_get_settings_option( 'cl_color' );
		$color = !empty( $color ) ? $color : '#efe72c';
		ob_start();
		?>
		/*=============================================
			Plugin Default Color
		=============================================*/
		.wi-bannertitle h1 span em
		{ color: <?php echo esc_html( $color ); ?>!important; }
		/*=== Theme Background Color ===*/
		.wi-btn,
		.wi-nav .navbar-toggler,
		.wi-searchbtn,
		.wi-relatedtags a:hover,
		.wi-package:hover .wi-packagefooter .wi-btn
		{ background: <?php echo esc_html( $color ); ?>!important; }
		/*=== Theme Border Color ===*/
		input:focus,
		.select select:focus,
		.form-control:focus,
		.wi-relatedtags a:hover
		{ border-color: <?php echo esc_html( $color ); ?>!important; }
		:root{--themecolor: <?php echo esc_html( $color ); ?>!important; !important;}
		/*=== secondary Colors Color ===*/
		.wi-packagelist .wi-checkpackage em i
		{ color: #487afa; }
		.wi-btntwo,
		.wi-pagination ul li.wi-prevpage a,
		.wi-pagination ul li.wi-nextpage a,
		.wi-package .wi-spackagetitle:before,
		.wi-pagepagination .cl-page-active,
		.wi-profilebtns .wi-btntwo{
			background-color: #487afa;	
		} 			
		<?php
		return ob_get_clean();
	}
}
?>
