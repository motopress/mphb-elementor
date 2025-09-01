<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Press Enter to proceed...' );
}

class MPHBElementor {

	const SLUG = 'mphb-elementor';
	const WIDGET_CATEGORY_NAME = 'motopress-hotel-booking';

	private static $instance = null;


	private function __construct() {

		// Check if the MotoPress Hotel Booking is active
		if ( ! class_exists( 'HotelBookingPlugin' ) ) {
			return;
		}

		// Check if the Elementor is active
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		// Check required version
		if ( ! version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {
			return;
		}

		add_action( 'init', array( $this, 'loadTextdomain' ), -1 );

		add_filter( 'elementor/elements/categories_registered', array( $this, 'registerCategories' ), 10, 1 );
		add_filter( 'elementor/widgets/register', array( $this, 'registerWidgets' ), 10, 1 );

		add_action( 'elementor/init', array( $this, 'addAvailableRoomsData' ) );
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueuePreviewStyles' ) );

	}


	public function loadTextdomain() {

		global $wp_version;

		$isWp47 = version_compare( $wp_version, '4.7', '>=' );

		$locale = $isWp47 ? get_user_locale() : get_locale();
		$locale = apply_filters( 'plugin_locale', $locale, self::SLUG );

		// wp-content/languages/mphb-elementor/mphb-elementor-{lang}_{locale}.mo
		$moFile = sprintf( '%1$s/%2$s/%2$s-%3$s.mo', WP_LANG_DIR, self::SLUG, $locale );

		load_textdomain( self::SLUG, $moFile );
		load_plugin_textdomain( self::SLUG, false, self::SLUG . '/languages' );
	}


	/**
	 * Note that the categories are displayed in the widgets panel, only if they
	 * have widgets assigned to them.
	 * @param \Elementor\Elements_Manager
	 */
	public function registerCategories( $elementsManager ) {

		$elementsManager->add_category(
			self::WIDGET_CATEGORY_NAME,
			array(
				'title' => __( 'MotoPress Hotel Booking', 'mphb-elementor' ),
				'icon'  => 'fa fa-plug',
			)
		);
	}

	protected function widgets() {

		require __DIR__ . '/widgets/abstract-widget.php';
		require __DIR__ . '/widgets/abstract-gallery-widget.php';
		require __DIR__ . '/widgets/abstract-calendar-widget.php';
		require __DIR__ . '/widgets/search-form-widget.php';
		require __DIR__ . '/widgets/search-results-widget.php';
		require __DIR__ . '/widgets/rooms-widget.php';
		require __DIR__ . '/widgets/room-widget.php';
		require __DIR__ . '/widgets/services-widget.php';
		require __DIR__ . '/widgets/rates-widget.php';
		require __DIR__ . '/widgets/availability-widget.php';
		require __DIR__ . '/widgets/booking-confirmation-widget.php';
		require __DIR__ . '/widgets/checkout-widget.php';
		require __DIR__ . '/widgets/availability-calendar-widget.php';

		require __DIR__ . '/widgets/accommodation/abstract-accommodation-widget.php';
		require __DIR__ . '/widgets/accommodation/featured-image-widget.php';
		require __DIR__ . '/widgets/accommodation/attribute-widget.php';
		require __DIR__ . '/widgets/accommodation/attributes-widget.php';
		require __DIR__ . '/widgets/accommodation/content-widget.php';
		require __DIR__ . '/widgets/accommodation/gallery-widget.php';
		require __DIR__ . '/widgets/accommodation/price-widget.php';
		require __DIR__ . '/widgets/accommodation/title-widget.php';

		return array(
			new \mphbe\widgets\SearchFormWidget(),
			new \mphbe\widgets\SearchResultsWidget(),
			new \mphbe\widgets\RoomsWidget(),
			new \mphbe\widgets\RoomWidget(),
			new \mphbe\widgets\ServicesWidget(),
			new \mphbe\widgets\RatesWidget(),
			new \mphbe\widgets\AvailabilityWidget(),
			new \mphbe\widgets\BookingConfirmationWidget(),
			new \mphbe\widgets\CheckoutWidget(),
			new \mphbe\widgets\AvailabilityCalendarWidget(),
			new \mphbe\widgets\AccommodationFeaturedImageWidget(),
			new \mphbe\widgets\AccommodationAttributeWidget(),
			new \mphbe\widgets\AccommodationAttributesWidget(),
			new \mphbe\widgets\AccommodationContentWidget(),
			new \mphbe\widgets\AccommodationGalleryWidget(),
			new \mphbe\widgets\AccommodationPriceWidget(),
			new \mphbe\widgets\AccommodationTitleWidget(),
		);
	}

	/**
	 * @param \Elementor\Widgets_Manager
	 */
	public function registerWidgets( $widgetsManager ) {

		foreach ( $this->widgets() as $widget ) {
			$widgetsManager->register( $widget );
		}
	}

	public function enqueuePreviewStyles() {

		wp_enqueue_style( 'mphb-flexslider-css' );
	}

	public function addAvailableRoomsData() {

		$readableStatuses = array( 'publish' );

		if ( current_user_can( 'read_private_posts' ) ) {
			$readableStatuses[] = 'private';
		}

		$roomTypes = MPHB()->getRoomTypePersistence()->getPosts(
			array(
				'post_status' => $readableStatuses,
			)
		);

		array_walk( $roomTypes, array( MPHB()->getPublicScriptManager(), 'addRoomTypeData' ) );
	}

	public static function create() {

		if ( is_null( self::$instance ) ) {

			self::$instance = new self();
		}
	}
}
