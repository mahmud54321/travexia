<?php

namespace Tourfic\App\Shortcodes;

defined( 'ABSPATH' ) || exit;

use \Tourfic\Classes\Helper;
use \Tourfic\Classes\Car_Rental\Availability;
use Tourfic\Classes\Hotel\Hotel;
use Tourfic\Classes\Tour\Tour;
use \Tourfic\Classes\Apartment\Apartment;

class Search_Result extends \Tourfic\Core\Shortcodes {

	use \Tourfic\Traits\Singleton;

	protected $shortcode = 'tf_search_result';

	function render( $atts, $content = null ) {

		// Unwanted Slashes Remove
		if ( isset( $_GET ) ) {
			$_GET = array_map( 'stripslashes_deep', $_GET );
		}

		// Get post type
		$post_type = isset( $_GET['type'] ) ? sanitize_text_field( $_GET['type'] ) : '';
		if ( empty( $post_type ) ) {
			echo '<h3>' . esc_html__(" Please select fields from the search form! ", "tourfic") . '</h3>';

			return;
		}
		// Get hotel location or tour destination
		$taxonomy     = $post_type == 'tf_hotel' ? 'hotel_location' : ( $post_type == 'tf_tours' ? 'tour_destination' : 'apartment_location' );
		$place        = isset( $_GET['place'] ) ? sanitize_text_field( $_GET['place'] ) : '';
		$adults       = isset( $_GET['adults'] ) ? sanitize_text_field( $_GET['adults'] ) : '';
		$child        = isset( $_GET['children'] ) ? sanitize_text_field( $_GET['children'] ) : '';
		$infant       = isset( $_GET['infant'] ) ? sanitize_text_field( $_GET['infant'] ) : '';
		$room         = isset( $_GET['room'] ) ? sanitize_text_field( $_GET['room'] ) : '';
		$check_in_out = isset( $_GET['check-in-out-date'] ) ? sanitize_text_field( $_GET['check-in-out-date'] ) : '';
		//get children ages
		//$children_ages = isset( $_GET['children_ages'] ) ? sanitize_text_field($_GET['children_ages']) : '';


		// Price Range
		$startprice = isset( $_GET['from'] ) ? absint( sanitize_key( $_GET['from'] ) ) : '';
		$endprice   = isset( $_GET['to'] ) ? absint( sanitize_key( $_GET['to'] ) ) : '';

		// Cars Data Start
		$pickup   = isset( $_GET['pickup'] ) ? sanitize_text_field( $_GET['pickup'] ) : '';
		$dropoff = isset( $_GET['dropoff'] ) ? sanitize_text_field( $_GET['dropoff'] ) : '';
		$tf_pickup_date  = isset( $_GET['pickup-date'] ) ? sanitize_text_field( $_GET['pickup-date'] ) : '';
		$tf_dropoff_date  = isset( $_GET['dropoff-date'] ) ? sanitize_text_field( $_GET['dropoff-date'] ) : '';
		$tf_pickup_time  = isset( $_GET['pickup-time'] ) ? sanitize_text_field( $_GET['pickup-time'] ) : '';
		$tf_dropoff_time  = isset( $_GET['dropoff-time'] ) ? sanitize_text_field( $_GET['dropoff-time'] ) : '';
		// Cars Data End

		// Author Id if any
		$tf_author_ids = isset( $_GET['tf-author'] ) ? sanitize_key( $_GET['tf-author'] ) : '';

		if ( ! empty( $startprice ) && ! empty( $endprice ) ) {
			if ( $_GET['type'] == "tf_tours" ) {
				$data = array( $adults, $child, $check_in_out, $startprice, $endprice );
			} elseif ( $_GET['type'] == "tf_apartment" ) {
				$data = array( $adults, $child, $infant, $check_in_out, $startprice, $endprice );
			} else {
				$data = array( $adults, $child, $room, $check_in_out, $startprice, $endprice );
			}
		} else {
			if ( $_GET['type'] == "tf_tours" ) {
				$data = array( $adults, $child, $check_in_out );
			} else {
				$data = array( $adults, $child, $room, $check_in_out );
			}
		}

		// Gird or List View
		if(!empty($_GET['type']) && $_GET['type'] == "tf_hotel"){
			$tf_defult_views = ! empty( Helper::tf_data_types(Helper::tfopt( 'tf-template' ))['hotel_archive_view'] ) ? Helper::tf_data_types(Helper::tfopt( 'tf-template' ))['hotel_archive_view'] : 'list';
		}elseif(!empty($_GET['type']) && $_GET['type'] == "tf_tours"){
			$tf_defult_views = ! empty( Helper::tf_data_types(Helper::tfopt( 'tf-template' ))['tour_archive_view'] ) ? Helper::tf_data_types(Helper::tfopt( 'tf-template' ))['tour_archive_view'] : 'list';
		}elseif(!empty($_GET['type']) && $_GET['type'] == "tf_apartment"){
			$tf_defult_views = ! empty( Helper::tf_data_types(Helper::tfopt( 'tf-template' ))['apartment_archive_view'] ) ? Helper::tf_data_types(Helper::tfopt( 'tf-template' ))['apartment_archive_view'] : 'list';
		}elseif(!empty($_GET['type']) && $_GET['type'] == "tf_carrental"){
			$tf_defult_views = ! empty( Helper::tf_data_types(Helper::tfopt( 'tf-template' ))['car_archive_view'] ) ? Helper::tf_data_types(Helper::tfopt( 'tf-template' ))['car_archive_view'] : 'grid';
		}else{

		}

		$paged          = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
		$checkInOutDate = ! empty( $_GET['check-in-out-date'] ) ? explode( ' - ', $_GET['check-in-out-date'] ) : '';
		if ( ! empty( $checkInOutDate ) ) {
			$period = new \DatePeriod(
				new \DateTime( $checkInOutDate[0] ),
				new \DateInterval( 'P1D' ),
				new \DateTime( ! empty( $checkInOutDate[1] ) ? $checkInOutDate[1] : $checkInOutDate[0] . '23:59' )
			);
		} else {
			$period = '';
		}

		$post_per_page = Helper::tfopt( 'posts_per_page' ) ? Helper::tfopt( 'posts_per_page' ) : 10;
		// Main Query args
		if ( $post_type == "tf_tours" ) {
			$tf_expired_tour_showing = ! empty( Helper::tfopt( 't-show-expire-tour' ) ) ? Helper::tfopt( 't-show-expire-tour' ) : '';
			if ( ! empty( $tf_expired_tour_showing ) ) {
				$tf_tour_posts_status = array( 'publish', 'expired' );
			} else {
				$tf_tour_posts_status = array( 'publish' );
			}
			$args = array(
				'post_type'      => $post_type,
				'post_status'    => $tf_tour_posts_status,
				'posts_per_page' => - 1,
				'author'         => $tf_author_ids,
			);
		} else {
			$args = array(
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
				'author'         => $tf_author_ids,
			);
		}

		$taxonomy_query = new \WP_Term_Query( array(
			'taxonomy'   => $taxonomy,
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => false,
			'slug'       => sanitize_title( $place, '' ),
		) );

		if ( $taxonomy_query ) {

			$place_ids = array();

			// Place IDs array
			foreach ( $taxonomy_query->get_terms() as $term ) {
				$place_ids[] = $term->term_id;
			}

			$args['tax_query'] = array(
				'relation' => 'AND',
				array(
					'taxonomy' => $taxonomy,
					'terms'    => $place_ids,
				)
			);

		} else {
			$args['s'] = $place;
		}


		// Hotel/Apartment Features
		if ( ! empty( $_GET['features'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => $post_type == 'tf_hotel' ? 'hotel_feature' : 'apartment_feature',
				'field'    => 'slug',
				'terms'    => $_GET['features'],
			);
		}
		// Hotel/Tour/Apartment Types
		if ( ! empty( $_GET['types'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => $post_type == 'tf_hotel' ? 'hotel_type' : ($post_type == 'tf_tours' ? 'tour_type' : 'apartment_type'),
				'field'    => 'slug',
				'terms'    => $_GET['types'],
			);
		}

		// Car Data Filter Start
		if(!empty($pickup)){
			$args['tax_query'] = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'carrental_location',
					'field'    => 'slug',
					'terms'    => sanitize_title( $pickup, '' ),
				),
			);
		}

		if(!empty($startprice) && !empty($endprice) && $post_type == 'tf_carrental'){
			$args['meta_query'] = array(
				array(
					'key' => 'tf_search_car_rent',
					'value'    => [$startprice, $endprice],
					'compare'    => 'BETWEEN',
					'type' => 'DECIMAL(10,3)'
				),
			);
		}
		$car_driver_min_age = ! empty( Helper::tf_data_types( Helper::tfopt( 'tf-template' ) )['car_archive_driver_min_age'] ) ? Helper::tf_data_types( Helper::tfopt( 'tf-template' ) )['car_archive_driver_min_age'] : 18;
        $car_driver_max_age = ! empty( Helper::tf_data_types( Helper::tfopt( 'tf-template' ) )['car_archive_driver_max_age'] ) ? Helper::tf_data_types( Helper::tfopt( 'tf-template' ) )['car_archive_driver_max_age'] : 40;
		if(!empty($_GET['driver_age']) && 'on'==$_GET['driver_age'] && $post_type == 'tf_carrental'){
			$args['meta_query'] = array(
				array(
					'key' => 'tf_search_driver_age',
					'value'    => [$car_driver_min_age, $car_driver_max_age],
					'compare'    => 'BETWEEN',
					'type' => 'DECIMAL(10,3)'
				),
			);
		}
		
		if (!empty($args['meta_query']) && count($args['meta_query']) > 1) {
			$args['meta_query']['relation'] = 'AND';
		}
		// Car Data Filter End

		$loop        = new \WP_Query( $args );
		$total_posts = $loop->found_posts;
		ob_start(); ?>
		<!-- Start Content -->
		<?php

		$tf_tour_arc_selected_template  = ! empty( Helper::tf_data_types( Helper::tfopt( 'tf-template' ) )['tour-archive'] ) ? Helper::tf_data_types( Helper::tfopt( 'tf-template' ) )['tour-archive'] : 'design-1';
		$tf_hotel_arc_selected_template = ! empty( Helper::tf_data_types( Helper::tfopt( 'tf-template' ) )['hotel-archive'] ) ? Helper::tf_data_types( Helper::tfopt( 'tf-template' ) )['hotel-archive'] : 'design-1';
		$tf_apartment_arc_selected_template = ! empty( Helper::tf_data_types(Helper::tfopt( 'tf-template' ))['apartment-archive'] ) ?  Helper::tf_data_types(Helper::tfopt( 'tf-template' ))['apartment-archive'] : 'default';
		$tf_car_arc_selected_template = ! empty( Helper::tf_data_types(Helper::tfopt( 'tf-template' ))['car-archive'] ) ?  Helper::tf_data_types(Helper::tfopt( 'tf-template' ))['car-archive'] : 'design-1';

		if ( ( $post_type == "tf_tours" && $tf_tour_arc_selected_template == "design-1" ) || ( $post_type == "tf_hotel" && $tf_hotel_arc_selected_template == "design-1" ) ) {
			?>
			<div class="tf-column tf-page-content tf-archive-left tf-result-previews">
				<!-- Search Head Section -->
				<div class="tf-archive-head tf-flex tf-flex-align-center tf-flex-space-bttn">
					<div class="tf-search-result tf-flex">
						<span class="tf-counter-title"><?php echo esc_html__( 'Total Results ', 'tourfic' ); ?> </span>
						<span><?php echo ' ('; ?> </span>
						<div class="tf-total-results">
							<span><?php echo esc_html( $total_posts ); ?> </span>
						</div>
						<span><?php echo ')'; ?> </span>
					</div>
					<div class="tf-search-layout tf-flex tf-flex-gap-12">
						<div class="tf-icon tf-serach-layout-list tf-grid-list-layout <?php echo $tf_defult_views=="list" ? esc_attr('active') : ''; ?>" data-id="list-view">
							<div class="defult-view">
								<svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
									<rect width="12" height="2" fill="#0E3DD8"/>
									<rect x="14" width="2" height="2" fill="#0E3DD8"/>
									<rect y="5" width="12" height="2" fill="#0E3DD8"/>
									<rect x="14" y="5" width="2" height="2" fill="#0E3DD8"/>
									<rect y="10" width="12" height="2" fill="#0E3DD8"/>
									<rect x="14" y="10" width="2" height="2" fill="#0E3DD8"/>
								</svg>
							</div>
							<div class="active-view">
								<svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
									<rect width="12" height="2" fill="white"/>
									<rect x="14" width="2" height="2" fill="white"/>
									<rect y="5" width="12" height="2" fill="white"/>
									<rect x="14" y="5" width="2" height="2" fill="white"/>
									<rect y="10" width="12" height="2" fill="white"/>
									<rect x="14" y="10" width="2" height="2" fill="white"/>
								</svg>
							</div>
						</div>
						<div class="tf-icon tf-serach-layout-grid tf-grid-list-layout <?php echo $tf_defult_views=="grid" ? esc_attr('active') : ''; ?>" data-id="grid-view">
							<div class="defult-view">
								<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
									<rect x="10" width="2" height="2" fill="#0E3DD8"/>
									<rect x="10" y="5" width="2" height="2" fill="#0E3DD8"/>
									<rect x="10" y="10" width="2" height="2" fill="#0E3DD8"/>
									<rect x="5" width="2" height="2" fill="#0E3DD8"/>
									<rect x="5" y="5" width="2" height="2" fill="#0E3DD8"/>
									<rect x="5" y="10" width="2" height="2" fill="#0E3DD8"/>
									<rect width="2" height="2" fill="#0E3DD8"/>
									<rect y="5" width="2" height="2" fill="#0E3DD8"/>
									<rect y="10" width="2" height="2" fill="#0E3DD8"/>
								</svg>
							</div>
							<div class="active-view">
								<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
									<rect x="10" width="2" height="2" fill="white"/>
									<rect x="10" y="5" width="2" height="2" fill="white"/>
									<rect x="10" y="10" width="2" height="2" fill="white"/>
									<rect x="5" width="2" height="2" fill="white"/>
									<rect x="5" y="5" width="2" height="2" fill="white"/>
									<rect x="5" y="10" width="2" height="2" fill="white"/>
									<rect width="2" height="2" fill="white"/>
									<rect y="5" width="2" height="2" fill="white"/>
									<rect y="10" width="2" height="2" fill="white"/>
								</svg>
							</div>
						</div>
						<div class="tf-sorting-selection-warper">
                            <form class="tf-archive-ordering" method="get">
                                <select class="tf-orderby" name="tf-orderby" id="tf-orderby">
                                    <option value="default">Default Sorting</option>
                                    <option value="enquiry">Sort By Recommended</option>
                                    <option value="order">Sort By Popularity</option>
                                    <option value="rating">Sort By Average Rating</option>
                                    <option value="latest">Sort By Latest</option>
                                    <option value="price-high">Sort By Price: High to Low</option>
                                    <option value="price-low">Sort By Price: Low to High</option>
                                </select>
                            </form>
                        </div>
					</div>
				</div>
				<!-- Loader Image -->
				<div id="tf_ajax_searchresult_loader">
					<div id="tf-searchresult-loader-img">
						<img src="<?php echo esc_url(TF_ASSETS_APP_URL) ?>images/loader.gif" alt="">
					</div>
				</div>
				<div class="tf-search-results-list tf-mt-30">
					<div class="archive_ajax_result tf-item-cards tf-flex <?php echo $tf_defult_views=="list" ? esc_attr('tf-layout-list') : esc_attr('tf-layout-grid'); ?>">
						<?php
						if ( $loop->have_posts() ) {
							$not_found = [];
							while ( $loop->have_posts() ) {
								$loop->the_post();

								if ( $post_type == 'tf_hotel' ) {

									if ( empty( $check_in_out ) ) {
										Hotel::tf_filter_hotel_without_date( $period, $not_found, $data );
									} else {
										Hotel::tf_filter_hotel_by_date( $period, $not_found, $data );
									}

								} else {

									if ( empty( $check_in_out ) ) {
										/**
										 * Check if minimum and maximum people limit matches with the search query
										 */
										$total_person = intval( $adults ) + intval( $child );
										$meta         = get_post_meta( get_the_ID(), 'tf_tours_opt', true );

										//skip the tour if the search form total people exceeds the maximum number of people in tour
										if ( ! empty( $meta['cont_max_people'] ) && $meta['cont_max_people'] < $total_person && $meta['cont_max_people'] != 0 ) {
											$total_posts --;
											continue;
										}

										//skip the tour if the search form total people less than the maximum number of people in tour
										if ( ! empty( $meta['cont_min_people'] ) && $meta['cont_min_people'] > $total_person && $meta['cont_min_people'] != 0 ) {
											$total_posts --;
											continue;
										}
										Tour::tf_filter_tour_by_without_date( $period, $total_posts, $not_found, $data );
									} else {
										Tour::tf_filter_tour_by_date( $period, $total_posts, $not_found, $data );
									}
								}

							}
							$tf_total_results = 0;
							$tf_total_filters = [];
							foreach ( $not_found as $not ) {
								if ( $not['found'] != 1 ) {
									$tf_total_results   = $tf_total_results + 1;
									$tf_total_filters[] = $not['post_id'];
								}
							}
							if ( empty( $tf_total_filters ) ) {
								echo '<div class="tf-nothing-found" data-post-count="0">' . esc_html__( 'Nothing Found!', 'tourfic' ) . '</div>';
							}
							$post_per_page = Helper::tfopt( 'posts_per_page' ) ? Helper::tfopt( 'posts_per_page' ) : 10;
							// Main Query args
							$filter_args = array(
								'post_type'      => $post_type,
								'post_status'    => 'publish',
								'posts_per_page' => $post_per_page,
								'paged'          => $paged,
							);


							$total_filtered_results = count( $tf_total_filters );
							$current_page           = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
							$offset                 = ( $current_page - 1 ) * $post_per_page;
							$displayed_results      = array_slice( $tf_total_filters, $offset, $post_per_page );
							if ( ! empty( $displayed_results ) ) {
								$filter_args = array(
									'post_type'      => $post_type,
									'post_status'    => 'publish',
									'posts_per_page' => $post_per_page,
									'post__in'       => $displayed_results,
								);


								$result_query = new \WP_Query( $filter_args );
								if ( $result_query->have_posts() ) {
									// Feature Posts
									while ( $result_query->have_posts() ) {
										$result_query->the_post();

										if ( $post_type == 'tf_hotel' ) {
											$hotel_meta = get_post_meta( get_the_ID() , 'tf_hotels_opt', true );

											if ( ! empty( $data ) ) {
												if ( isset( $data[4] ) && isset( $data[5] ) ) {
													[ $adults, $child, $room, $check_in_out, $startprice, $endprice ] = $data;
													if( $hotel_meta["featured"] ) Hotel::tf_hotel_archive_single_item( $adults, $child, $room, $check_in_out, $startprice, $endprice );
												} else {
													[ $adults, $child, $room, $check_in_out ] = $data;
													if( $hotel_meta["featured"] ) Hotel::tf_hotel_archive_single_item( $adults, $child, $room, $check_in_out );
												}
											} else {
												if( $hotel_meta["featured"] ) Hotel::tf_hotel_archive_single_item();
											}

										} elseif ( $post_type == 'tf_tours' ) {
											$tour_meta = get_post_meta( get_the_ID() , 'tf_tours_opt', true );
											if ( ! empty( $data ) ) {
												if ( isset( $data[3] ) && isset( $data[4] ) ) {
													[ $adults, $child, $check_in_out, $startprice, $endprice ] = $data;
													if( $tour_meta["tour_as_featured"] ) Tour::tf_tour_archive_single_item( $adults, $child, $check_in_out, $startprice, $endprice );
												} else {
													[ $adults, $child, $check_in_out ] = $data;
													if( $tour_meta["tour_as_featured"] ) Tour::tf_tour_archive_single_item( $adults, $child, $check_in_out );
												}
											} else {
												if( $tour_meta["tour_as_featured"] ) Tour::tf_tour_archive_single_item();
											}
										} else {
											$apartment_meta = get_post_meta( get_the_ID() , 'tf_apartment_opt', true );
											if ( ! empty( $data ) ) {
												if ( isset( $data[4] ) && isset( $data[5] ) ) {
													if( $apartment_meta["apartment_as_featured"] ) Apartment::tf_apartment_archive_single_item( $data );
												} else {
													if( $apartment_meta["apartment_as_featured"] ) Apartment::tf_apartment_archive_single_item( $data );
												}
											} else {
												if( $apartment_meta["apartment_as_featured"] ) Apartment::tf_apartment_archive_single_item();
											}
										}

									}
									// Other Posts
									while ( $result_query->have_posts() ) {
										$result_query->the_post();

										if ( $post_type == 'tf_hotel' ) {
											$hotel_meta = get_post_meta( get_the_ID() , 'tf_hotels_opt', true );
											if ( ! empty( $data ) ) {
												if ( isset( $data[4] ) && isset( $data[5] ) ) {
													[ $adults, $child, $room, $check_in_out, $startprice, $endprice ] = $data;
													if( ! $hotel_meta["featured"] ) Hotel::tf_hotel_archive_single_item( $adults, $child, $room, $check_in_out, $startprice, $endprice );
												} else {
													[ $adults, $child, $room, $check_in_out ] = $data;
													if( ! $hotel_meta["featured"] ) Hotel::tf_hotel_archive_single_item( $adults, $child, $room, $check_in_out );
												}
											} else {
												if( ! $hotel_meta["featured"] ) Hotel::tf_hotel_archive_single_item();
											}

										} elseif ( $post_type == 'tf_tours' ) {
											$tour_meta = get_post_meta( get_the_ID() , 'tf_tours_opt', true );
											if ( ! empty( $data ) ) {
												if ( isset( $data[3] ) && isset( $data[4] ) ) {
													[ $adults, $child, $check_in_out, $startprice, $endprice ] = $data;
													if( ! $tour_meta["tour_as_featured"] ) Tour::tf_tour_archive_single_item( $adults, $child, $check_in_out, $startprice, $endprice );
												} else {
													[ $adults, $child, $check_in_out ] = $data;
													if( ! $tour_meta["tour_as_featured"] ) Tour::tf_tour_archive_single_item( $adults, $child, $check_in_out );
												}
											} else {
												if( ! $tour_meta["tour_as_featured"] )	Tour::tf_tour_archive_single_item();
											}
										} else {
											$apartment_meta = get_post_meta( get_the_ID() , 'tf_apartment_opt', true );
											if ( ! empty( $data ) ) {
												if ( isset( $data[4] ) && isset( $data[5] ) ) {
													if( ! $apartment_meta["apartment_as_featured"] ) Apartment::tf_apartment_archive_single_item( $data );
												} else {
													if( ! $apartment_meta["apartment_as_featured"] ) Apartment::tf_apartment_archive_single_item( $data );
												}
											} else {
												if( ! $apartment_meta["apartment_as_featured"] ) Apartment::tf_apartment_archive_single_item();
											}
										}

									}
								}
								$total_pages = ceil( $total_filtered_results / $post_per_page );
								echo "<div class='tf_posts_navigation tf_posts_page_navigation'>";
								echo wp_kses_post(
									paginate_links( array(
										'total'   => $total_pages,
										'current' => $current_page
									) )
								);
								echo "</div>";
							}

						} else {
							echo '<div class="tf-nothing-found" data-post-count="0">' . esc_html__( 'Nothing Found!', 'tourfic' ) . '</div>';
						}
						echo "<span hidden=hidden class='tf-posts-count'>";
						echo ! empty( $tf_total_results ) ? esc_html( $tf_total_results ) : 0;
						echo "</span>";
						?>

					</div>
				</div>
			</div>
			<?php
		}
		elseif ( ( $post_type == "tf_tours" && $tf_tour_arc_selected_template == "design-2" ) || ( $post_type == "tf_hotel" && $tf_hotel_arc_selected_template == "design-2" ) || ( $post_type == "tf_apartment" && $tf_apartment_arc_selected_template == "design-1" ) ) { ?>


			<div class="tf-available-archive-hetels-wrapper tf-available-rooms-wrapper" id="tf-hotel-rooms">
				<div class="tf-archive-available-rooms-head tf-available-rooms-head">
					<span class="tf-total-results">
							<?php esc_html_e("Total", "tourfic"); ?> <span><?php echo esc_html( $total_posts ); ?></span>
						<?php if($post_type == "tf_hotel"){
							esc_html_e("hotels available", "tourfic");
						}elseif($post_type == "tf_apartment"){
							esc_html_e("apartments available", "tourfic");
						}else{
							esc_html_e("tours available", "tourfic");
						} ?>
					</span>
					<div class="tf-archive-filter-showing">
						<i class="ri-equalizer-line"></i>
					</div>
					<div class="tf-sorting-selection-warper">
						<form class="tf-archive-ordering" method="get">
							<select class="tf-orderby" name="tf-orderby" id="tf-orderby">
								<option value="default"><?php echo esc_html__( 'Default Sorting', 'tourfic' ); ?></option>
								<option value="enquiry"><?php echo esc_html__( 'Sort By Recommended', 'tourfic' ); ?></option>
								<option value="order"><?php echo esc_html__( 'Sort By Popularity', 'tourfic' ); ?></option>
								<option value="rating"><?php echo esc_html__( 'Sort By Average Rating', 'tourfic' ); ?></option>
								<option value="latest"><?php echo esc_html__( 'Sort By Latest', 'tourfic' ); ?></option>
								<option value="price-high"><?php echo esc_html__( 'Sort By Price: High to Low', 'tourfic' ); ?></option>
								<option value="price-low"><?php echo esc_html__( 'Sort By Price: Low to High', 'tourfic' ); ?></option>
							</select>
						</form>
					</div>
				</div>

				<!-- Loader Image -->
				<div id="tour_room_details_loader">
					<div id="tour-room-details-loader-img">
						<img src="<?php echo esc_url(TF_ASSETS_APP_URL) ?>images/loader.gif" alt="">
					</div>
				</div>

				<!--Available rooms start -->
				<div class="tf-archive-available-rooms tf-available-rooms archive_ajax_result">

					<?php
					if ( $loop->have_posts() ) {
						$not_found = [];
						while ( $loop->have_posts() ) {
							$loop->the_post();

							if ( $post_type == 'tf_hotel' ) {

								if ( empty( $check_in_out ) ) {
									Hotel::tf_filter_hotel_without_date( $period, $not_found, $data );
								} else {
									Hotel::tf_filter_hotel_by_date( $period, $not_found, $data );
								}

							} elseif( $post_type == 'tf_tours' ) {

								if ( empty( $check_in_out ) ) {
									/**
									 * Check if minimum and maximum people limit matches with the search query
									 */
									$total_person = intval( $adults ) + intval( $child );
									$meta         = get_post_meta( get_the_ID(), 'tf_tours_opt', true );

									//skip the tour if the search form total people exceeds the maximum number of people in tour
									if ( ! empty( $meta['cont_max_people'] ) && $meta['cont_max_people'] < $total_person && $meta['cont_max_people'] != 0 ) {
										$total_posts --;
										continue;
									}

									//skip the tour if the search form total people less than the maximum number of people in tour
									if ( ! empty( $meta['cont_min_people'] ) && $meta['cont_min_people'] > $total_person && $meta['cont_min_people'] != 0 ) {
										$total_posts --;
										continue;
									}
									Tour::tf_filter_tour_by_without_date( $period, $total_posts, $not_found, $data );
								} else {
									Tour::tf_filter_tour_by_date( $period, $total_posts, $not_found, $data );
								}
							}else {
								if ( empty( $check_in_out ) ) {
									Apartment::tf_filter_apartment_without_date( $period, $not_found, $data );
								} else {
									Apartment::tf_filter_apartment_by_date( $period, $not_found, $data );
								}
							}

						}
						$tf_total_results = 0;
						$tf_total_filters = [];
						foreach ( $not_found as $not ) {
							if ( $not['found'] != 1 ) {
								$tf_total_results   = $tf_total_results + 1;
								$tf_total_filters[] = $not['post_id'];
							}
						}
						if ( empty( $tf_total_filters ) ) {
							echo '<div class="tf-nothing-found" data-post-count="0">' . esc_html__( 'Nothing Found!', 'tourfic' ) . '</div>';
						}
						$post_per_page = Helper::tfopt( 'posts_per_page' ) ? Helper::tfopt( 'posts_per_page' ) : 10;
						// Main Query args
						$filter_args = array(
							'post_type'      => $post_type,
							'post_status'    => 'publish',
							'posts_per_page' => $post_per_page,
							'paged'          => $paged,
						);


						$total_filtered_results = count( $tf_total_filters );
						$current_page           = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
						$offset                 = ( $current_page - 1 ) * $post_per_page;
						$displayed_results      = array_slice( $tf_total_filters, $offset, $post_per_page );
						if ( ! empty( $displayed_results ) ) {
							$filter_args = array(
								'post_type'      => $post_type,
								'post_status'    => 'publish',
								'posts_per_page' => $post_per_page,
								'post__in'       => $displayed_results,
							);

							$result_query = new \WP_Query( $filter_args );
							if ( $result_query->have_posts() ) {
								// Feature Posts
								while ( $result_query->have_posts() ) {
									$result_query->the_post();

									if ( $post_type == 'tf_hotel' ) {
										$hotel_meta = get_post_meta( get_the_ID() , 'tf_hotels_opt', true );

										if ( ! empty( $data ) ) {
											if ( isset( $data[4] ) && isset( $data[5] ) ) {
												[ $adults, $child, $room, $check_in_out, $startprice, $endprice ] = $data;
												if( $hotel_meta["featured"] ) Hotel::tf_hotel_archive_single_item( $adults, $child, $room, $check_in_out, $startprice, $endprice );
											} else {
												[ $adults, $child, $room, $check_in_out ] = $data;
												if( $hotel_meta["featured"] ) Hotel::tf_hotel_archive_single_item( $adults, $child, $room, $check_in_out );
											}
										} else {
											if( $hotel_meta["featured"] ) Hotel::tf_hotel_archive_single_item();
										}

									} elseif ( $post_type == 'tf_tours' ) {
										$tour_meta = get_post_meta( get_the_ID() , 'tf_tours_opt', true );
										if ( ! empty( $data ) ) {
											if ( isset( $data[3] ) && isset( $data[4] ) ) {
												[ $adults, $child, $check_in_out, $startprice, $endprice ] = $data;
												if( $tour_meta["tour_as_featured"] ) Tour::tf_tour_archive_single_item( $adults, $child, $check_in_out, $startprice, $endprice );
											} else {
												[ $adults, $child, $check_in_out ] = $data;
												if( $tour_meta["tour_as_featured"] ) Tour::tf_tour_archive_single_item( $adults, $child, $check_in_out );
											}
										} else {
											if( $tour_meta["tour_as_featured"] ) Tour::tf_tour_archive_single_item();
										}
									} else {
										$apartment_meta = get_post_meta( get_the_ID() , 'tf_apartment_opt', true );
										if ( ! empty( $data ) ) {
											if ( isset( $data[4] ) && isset( $data[5] ) ) {
												if( $apartment_meta["apartment_as_featured"] ) Apartment::tf_apartment_archive_single_item( $data );
											} else {
												if( $apartment_meta["apartment_as_featured"] ) Apartment::tf_apartment_archive_single_item( $data );
											}
										} else {
											if( $apartment_meta["apartment_as_featured"] ) Apartment::tf_apartment_archive_single_item();
										}
									}

								}
								// Other Posts
								while ( $result_query->have_posts() ) {
									$result_query->the_post();

									if ( $post_type == 'tf_hotel' ) {
										$hotel_meta = get_post_meta( get_the_ID() , 'tf_hotels_opt', true );
										if ( ! empty( $data ) ) {
											if ( isset( $data[4] ) && isset( $data[5] ) ) {
												[ $adults, $child, $room, $check_in_out, $startprice, $endprice ] = $data;
												if( ! $hotel_meta["featured"] ) Hotel::tf_hotel_archive_single_item( $adults, $child, $room, $check_in_out, $startprice, $endprice );
											} else {
												[ $adults, $child, $room, $check_in_out ] = $data;
												if( ! $hotel_meta["featured"] ) Hotel::tf_hotel_archive_single_item( $adults, $child, $room, $check_in_out );
											}
										} else {
											if( ! $hotel_meta["featured"] ) Hotel::tf_hotel_archive_single_item();
										}

									} elseif ( $post_type == 'tf_tours' ) {
										$tour_meta = get_post_meta( get_the_ID() , 'tf_tours_opt', true );
										if ( ! empty( $data ) ) {
											if ( isset( $data[3] ) && isset( $data[4] ) ) {
												[ $adults, $child, $check_in_out, $startprice, $endprice ] = $data;
												if( ! $tour_meta["tour_as_featured"] ) Tour::tf_tour_archive_single_item( $adults, $child, $check_in_out, $startprice, $endprice );
											} else {
												[ $adults, $child, $check_in_out ] = $data;
												if( ! $tour_meta["tour_as_featured"] ) Tour::tf_tour_archive_single_item( $adults, $child, $check_in_out );
											}
										} else {
											if( ! $tour_meta["tour_as_featured"] )	Tour::tf_tour_archive_single_item();
										}
									} else {
										$apartment_meta = get_post_meta( get_the_ID() , 'tf_apartment_opt', true );
										if ( ! empty( $data ) ) {
											if ( isset( $data[4] ) && isset( $data[5] ) ) {
												if( ! $apartment_meta["apartment_as_featured"] ) Apartment::tf_apartment_archive_single_item( $data );
											} else {
												if( ! $apartment_meta["apartment_as_featured"] ) Apartment::tf_apartment_archive_single_item( $data );
											}
										} else {
											if( ! $apartment_meta["apartment_as_featured"] ) Apartment::tf_apartment_archive_single_item();
										}
									}

								}
							}
							$total_pages = ceil( $total_filtered_results / $post_per_page );
							if($total_pages > 1){
								echo "<div class='tf_posts_navigation tf_posts_page_navigation'>";
								echo wp_kses_post(
									paginate_links( array(
										'total'   => $total_pages,
										'current' => $current_page
									) )
								);
								echo "</div>";
							}
						}

					} else {
						echo '<div class="tf-nothing-found" data-post-count="0">' . esc_html__( 'Nothing Found!', 'tourfic' ) . '</div>';
					}
					echo "<span hidden=hidden class='tf-posts-count'>";
					echo ! empty( $tf_total_results ) ? esc_html( $tf_total_results ) : 0;
					echo "</span>";
					?>

				</div>
				<!-- Available rooms end -->

			</div>
		<?php }	elseif ( ( $post_type == "tf_carrental" && $tf_car_arc_selected_template == "design-1" ) ) { ?>

			<div class="tf-archive-header tf-flex tf-flex-space-bttn tf-flex-align-center tf-mb-30">
				<div class="tf-archive-view">
					<ul class="tf-flex tf-flex-gap-16">
						<li class="<?php echo $tf_defult_views=="grid" ? esc_attr('active') : ''; ?>" data-view="grid"><i class="ri-layout-grid-line"></i></li>
						<li class="<?php echo $tf_defult_views=="list" ? esc_attr('active') : ''; ?>" data-view="list"><i class="ri-list-check"></i></li>
					</ul>
				</div>
				<div class="tf-total-result-bar">
					<span>
						<?php echo esc_html__( 'Total Results ', 'tourfic' ); ?>
					</span>
					<span><?php echo ' ('; ?> </span>
					<div class="tf-total-results">
						<span><?php echo esc_html( $total_posts ); ?> </span>
					</div>
					<span><?php echo ')'; ?> </span>
				</div>
			</div>
			<div class="tf-car-details-column tf-flex tf-flex-gap-32">
				
				<div class="tf-car-archive-sidebar">
					<div class="tf-sidebar-header tf-flex tf-flex-space-bttn tf-flex-align-center">
						<h4><?php esc_html_e("Filter", "tourfic") ?></h4>
						<button class="filter-reset-btn"><?php esc_html_e("Reset", "tourfic"); ?></button>
					</div>
					
					<?php if ( is_active_sidebar( 'tf_search_result' ) ) { 
						dynamic_sidebar( 'tf_search_result' );
					} ?>

				</div>

				<div class="tf-car-archive-result">
					
					<div class="tf-car-result archive_ajax_result tf-flex tf-flex-gap-32 <?php echo $tf_defult_views=="list" ? esc_attr('list-view') : esc_attr('grid-view'); ?>">
						
						<?php
						if ( $loop->have_posts() ) {
							$not_found = [];
							while ( $loop->have_posts() ) {
								$loop->the_post();
	
								if ( $post_type == 'tf_carrental' ) {
									$car_meta = get_post_meta( get_the_ID(), 'tf_carrental_opt', true );
									$car_inventory = Availability::tf_car_inventory(get_the_ID(), $car_meta, $tf_pickup_date, $tf_dropoff_date, $tf_pickup_time, $tf_dropoff_time);
									if($car_inventory){
										tf_car_availability_response($car_meta, $not_found, $pickup, $dropoff, $tf_pickup_date, $tf_dropoff_date, $tf_pickup_time, $tf_dropoff_time, $startprice, $endprice);
									}
								}
							}

							$tf_total_results = 0;
							$tf_total_filters = [];
							foreach ( $not_found as $not ) {
								if ( $not['found'] != 1 ) {
									$tf_total_results   = $tf_total_results + 1;
									$tf_total_filters[] = $not['post_id'];
								}
							}

							if ( empty( $tf_total_filters ) ) {
								echo '<div class="tf-nothing-found" data-post-count="0">' . esc_html__( 'Nothing Found!', 'tourfic' ) . '</div>';
							}
							
							$post_per_page = Helper::tfopt( 'posts_per_page' ) ? Helper::tfopt( 'posts_per_page' ) : 10;

							$total_filtered_results = count( $tf_total_filters );
							$current_page           = ! empty( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
							$offset                 = ( $current_page - 1 ) * $post_per_page;
							$displayed_results      = array_slice( $tf_total_filters, $offset, $post_per_page );
							if ( ! empty( $displayed_results ) ) {
								$filter_args = array(
									'post_type'      => $post_type,
									'posts_per_page' => $post_per_page,
									'post__in'       => $displayed_results,
								);

								$result_query  = new \WP_Query( $filter_args );
								$result_query2 = $result_query;
								if ( $result_query->have_posts() ) {
									while ( $result_query->have_posts() ) {
										$result_query->the_post();

										if ( $post_type == 'tf_carrental' ) {
											$car_meta = get_post_meta( get_the_ID(), 'tf_carrental_opt', true );
											if ( $car_meta["car_as_featured"] ) {
												tf_car_archive_single_item($pickup, $dropoff, $tf_pickup_date, $tf_dropoff_date, $tf_pickup_time, $tf_dropoff_time);
											}
										}

									}

									while ( $result_query2->have_posts() ) {
										$result_query2->the_post();

										if ( $post_type == 'tf_carrental' ) {
											$car_meta = get_post_meta( get_the_ID(), 'tf_carrental_opt', true );
											if ( ! $car_meta["car_as_featured"] ) {
												tf_car_archive_single_item($pickup, $dropoff, $tf_pickup_date, $tf_dropoff_date, $tf_pickup_time, $tf_dropoff_time);
											}
										}

									}
								}
								$total_pages = ceil( $total_filtered_results / $post_per_page );
								if ( $total_pages > 1 ) {
									echo "<div class='tf_posts_navigation tf_posts_ajax_navigation'>";
									echo wp_kses_post(
										paginate_links( array(
											'total'   => $total_pages,
											'current' => $current_page
										) )
									);
									echo "</div>";
								}
							}
						} else {
							echo '<div class="tf-nothing-found" data-post-count="0">' . esc_html__( 'Nothing Found!', 'tourfic' ) . '</div>';
						}

						echo "<span hidden=hidden class='tf-posts-count'>";
						echo ! empty( $tf_total_results ) ? esc_html( $tf_total_results ) : 0;
						echo "</span>";
						wp_reset_postdata();
					?>

					</div>

				</div>

			</div>
			
		<?php } else { ?>
			<div class="tf_search_result">
				<div class="tf-action-top">
					<div class="tf-total-results">
						<?php echo esc_html__( 'Total Results ', 'tourfic' ) . '(<span>' . esc_html( $total_posts ) . '</span>)'; ?>
					</div>
					<div class="tf-list-grid">
						<a href="#list-view" data-id="list-view" class="change-view <?php echo $tf_defult_views=="list" ? esc_attr('active') : ''; ?>" title="<?php esc_html_e( 'List View', 'tourfic' ); ?>"><i class="fas fa-list"></i></a>
						<a href="#grid-view" data-id="grid-view" class="change-view <?php echo $tf_defult_views=="grid" ? esc_attr('active') : ''; ?>" title="<?php esc_html_e( 'Grid View', 'tourfic' ); ?>"><i class="fas fa-border-all"></i></a>
						<div class="tf-sorting-selection-warper">
                            <form class="tf-archive-ordering" method="get">
                                <select class="tf-orderby" name="tf-orderby" id="tf-orderby">
                                    <option value="default"><?php echo esc_html__( 'Default Sorting', 'tourfic' ); ?></option>
                                    <option value="enquiry"><?php echo esc_html__( 'Sort By Recommended', 'tourfic' ); ?></option>
                                    <option value="order"><?php echo esc_html__( 'Sort By Popularity', 'tourfic' ); ?></option>
                                    <option value="rating"><?php echo esc_html__( 'Sort By Average Rating', 'tourfic' ); ?></option>
                                    <option value="latest"><?php echo esc_html__( 'Sort By Latest', 'tourfic' ); ?></option>
                                    <option value="price-high"><?php echo esc_html__( 'Sort By Price: High to Low', 'tourfic' ); ?></option>
                                    <option value="price-low"><?php echo esc_html__( 'Sort By Price: Low to High', 'tourfic' ); ?></option>
                                </select>
                            </form>
                        </div>
					</div>
				</div>
				<div class="archive_ajax_result <?php echo $tf_defult_views=="grid" ? esc_attr('tours-grid') : '' ?>">
					<?php
					if ( $loop->have_posts() ) {
						$not_found = [];
						while ( $loop->have_posts() ) {
							$loop->the_post();

							if ( $post_type == 'tf_hotel' ) {

								if ( empty( $check_in_out ) ) {
									Hotel::tf_filter_hotel_without_date( $period, $not_found, $data );
								} else {
									Hotel::tf_filter_hotel_by_date( $period, $not_found, $data );
								}

							} elseif ( $post_type == 'tf_tours' ) {
								if ( empty( $check_in_out ) ) {
									/**
									 * Check if minimum and maximum people limit matches with the search query
									 */
									$total_person = intval( $adults ) + intval( $child );
									$meta         = get_post_meta( get_the_ID(), 'tf_tours_opt', true );

									//skip the tour if the search form total people exceeds the maximum number of people in tour
									if ( ! empty( $meta['cont_max_people'] ) && $meta['cont_max_people'] < $total_person && $meta['cont_max_people'] != 0 ) {
										$total_posts --;
										continue;
									}

									//skip the tour if the search form total people less than the maximum number of people in tour
									if ( ! empty( $meta['cont_min_people'] ) && $meta['cont_min_people'] > $total_person && $meta['cont_min_people'] != 0 ) {
										$total_posts --;
										continue;
									}
									Tour::tf_filter_tour_by_without_date( $period, $total_posts, $not_found, $data );
								} else {
									Tour::tf_filter_tour_by_date( $period, $total_posts, $not_found, $data );
								}
							} else {
								if ( empty( $check_in_out ) ) {
									Apartment::tf_filter_apartment_without_date( $period, $not_found, $data );
								} else {
									Apartment::tf_filter_apartment_by_date( $period, $not_found, $data );
								}
							}

						}
						$tf_total_results = 0;
						$tf_total_filters = [];
						foreach ( $not_found as $not ) {
							if ( $not['found'] != 1 ) {
								$tf_total_results   = $tf_total_results + 1;
								$tf_total_filters[] = $not['post_id'];
							}
						}
						if ( empty( $tf_total_filters ) ) {
							echo '<div class="tf-nothing-found" data-post-count="0">' . esc_html__( 'Nothing Found!', 'tourfic' ) . '</div>';
						}
						$post_per_page = Helper::tfopt( 'posts_per_page' ) ? Helper::tfopt( 'posts_per_page' ) : 10;
						// Main Query args
						$filter_args = array(
							'post_type'      => $post_type,
							'posts_per_page' => $post_per_page,
							'paged'          => $paged,
						);


						$total_filtered_results = count( $tf_total_filters );
						$current_page           = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
						$offset                 = ( $current_page - 1 ) * $post_per_page;
						$displayed_results      = array_slice( $tf_total_filters, $offset, $post_per_page );
						if ( ! empty( $displayed_results ) ) {
							$filter_args = array(
								'post_type'      => $post_type,
								'posts_per_page' => $post_per_page,
								'post__in'       => $displayed_results,
							);


							$result_query = new \WP_Query( $filter_args );
							if ( $result_query->have_posts() ) {
								// Feature Posts
								while ( $result_query->have_posts() ) {
									$result_query->the_post();

									if ( $post_type == 'tf_hotel' ) {
										$hotel_meta = get_post_meta( get_the_ID() , 'tf_hotels_opt', true );

										if ( ! empty( $data ) ) {
											if ( isset( $data[4] ) && isset( $data[5] ) ) {
												[ $adults, $child, $room, $check_in_out, $startprice, $endprice ] = $data;
												if( $hotel_meta["featured"] ) Hotel::tf_hotel_archive_single_item( $adults, $child, $room, $check_in_out, $startprice, $endprice );
											} else {
												[ $adults, $child, $room, $check_in_out ] = $data;
												if( $hotel_meta["featured"] ) Hotel::tf_hotel_archive_single_item( $adults, $child, $room, $check_in_out );
											}
										} else {
											if( $hotel_meta["featured"] ) Hotel::tf_hotel_archive_single_item();
										}

									} elseif ( $post_type == 'tf_tours' ) {
										$tour_meta = get_post_meta( get_the_ID() , 'tf_tours_opt', true );
										if ( ! empty( $data ) ) {
											if ( isset( $data[3] ) && isset( $data[4] ) ) {
												[ $adults, $child, $check_in_out, $startprice, $endprice ] = $data;
												if( $tour_meta["tour_as_featured"] ) Tour::tf_tour_archive_single_item( $adults, $child, $check_in_out, $startprice, $endprice );
											} else {
												[ $adults, $child, $check_in_out ] = $data;
												if( $tour_meta["tour_as_featured"] ) Tour::tf_tour_archive_single_item( $adults, $child, $check_in_out );
											}
										} else {
											if( $tour_meta["tour_as_featured"] ) Tour::tf_tour_archive_single_item();
										}
									} else {
										$apartment_meta = get_post_meta( get_the_ID() , 'tf_apartment_opt', true );
										if ( ! empty( $data ) ) {
											if ( isset( $data[4] ) && isset( $data[5] ) ) {
												if( $apartment_meta["apartment_as_featured"] ) Apartment::tf_apartment_archive_single_item( $data );
											} else {
												if( $apartment_meta["apartment_as_featured"] ) Apartment::tf_apartment_archive_single_item( $data );
											}
										} else {
											if( $apartment_meta["apartment_as_featured"] ) Apartment::tf_apartment_archive_single_item();
										}
									}

								}
								// Other Posts
								while ( $result_query->have_posts() ) {
									$result_query->the_post();

									if ( $post_type == 'tf_hotel' ) {
										$hotel_meta = get_post_meta( get_the_ID() , 'tf_hotels_opt', true );
										if ( ! empty( $data ) ) {
											if ( isset( $data[4] ) && isset( $data[5] ) ) {
												[ $adults, $child, $room, $check_in_out, $startprice, $endprice ] = $data;
												if( ! $hotel_meta["featured"] ) Hotel::tf_hotel_archive_single_item( $adults, $child, $room, $check_in_out, $startprice, $endprice );
											} else {
												[ $adults, $child, $room, $check_in_out ] = $data;
												if( ! $hotel_meta["featured"] ) Hotel::tf_hotel_archive_single_item( $adults, $child, $room, $check_in_out );
											}
										} else {
											if( ! $hotel_meta["featured"] ) Hotel::tf_hotel_archive_single_item();
										}

									} elseif ( $post_type == 'tf_tours' ) {
										$tour_meta = get_post_meta( get_the_ID() , 'tf_tours_opt', true );
										if ( ! empty( $data ) ) {
											if ( isset( $data[3] ) && isset( $data[4] ) ) {
												[ $adults, $child, $check_in_out, $startprice, $endprice ] = $data;
												if( ! $tour_meta["tour_as_featured"] ) Tour::tf_tour_archive_single_item( $adults, $child, $check_in_out, $startprice, $endprice );
											} else {
												[ $adults, $child, $check_in_out ] = $data;
												if( ! $tour_meta["tour_as_featured"] ) Tour::tf_tour_archive_single_item( $adults, $child, $check_in_out );
											}
										} else {
											if( ! $tour_meta["tour_as_featured"] )	Tour::tf_tour_archive_single_item();
										}
									} else {
										$apartment_meta = get_post_meta( get_the_ID() , 'tf_apartment_opt', true );
										if ( ! empty( $data ) ) {
											if ( isset( $data[4] ) && isset( $data[5] ) ) {
												if( ! $apartment_meta["apartment_as_featured"] ) Apartment::tf_apartment_archive_single_item( $data );
											} else {
												if( ! $apartment_meta["apartment_as_featured"] ) Apartment::tf_apartment_archive_single_item( $data );
											}
										} else {
											if( ! $apartment_meta["apartment_as_featured"] ) Apartment::tf_apartment_archive_single_item();
										}
									}

								}
							}
							$total_pages = ceil( $total_filtered_results / $post_per_page );
							echo "<div class='tf_posts_navigation tf_posts_page_navigation'>";
							echo wp_kses_post(
								paginate_links( array(
									'total'   => $total_pages,
									'current' => $current_page,
								) )
							);
							echo "</div>";
						}

					} else {
						echo '<div class="tf-nothing-found" data-post-count="0">' . esc_html__( 'Nothing Found!', 'tourfic' ) . '</div>';
					}
					echo "<span hidden=hidden class='tf-posts-count'>";
					echo ! empty( $tf_total_results ) ? esc_html( $tf_total_results ) : 0;
					echo "</span>";
					?>
				</div>

			</div>
		<?php } ?>
		<!-- End Content -->

		<?php
		wp_reset_postdata(); ?>
		<?php return ob_get_clean();
	}
}