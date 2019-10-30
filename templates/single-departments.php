<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
/** Start code HERE **/
get_header(); ?>

<div id="primary">
  <?php
    /**
     * woocommerce_before_main_content hook.
     *
     * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
     * @hooked woocommerce_breadcrumb - 20
     */
    remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10, 2 ); 
    do_action( 'woocommerce_before_main_content' );
  ?>

    <div class="departments" role="main" >

    <?php 
    $lang = get_locale();
        if ($lang == 'uk_UA'){ 
            $department = 'Відділення:';
            $dep_address = 'Адреса:';
            $dep_phones = 'Телефони:';
            $dep_hours = 'Графік роботи:';
            $dep_offer = 'Ми пропонуємо:';
            $dep_map = 'Ми на карті:';            
          }
        elseif ($lang == 'ru_RU') {
            $department = 'Отделение:';
            $dep_address = 'Адрес:';
            $dep_phones = 'Телефоны:';
            $dep_hours = 'График работы:';
            $dep_offer = 'Мы предлагаем:';
            $dep_map = 'Мы на карте:'; 
          }
         else{
            $department = 'Department:';
            $dep_address = 'Address:';
            $dep_phones = 'Phones:';
            $dep_hours = 'Working hours:';
            $dep_offer = 'We offer:';
            $dep_map = 'We on the map:'; 
         } 

    while ( have_posts() ) : the_post();?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h3><?php echo $department; the_title(); ?></h3>
                <div class="image-sm">
                    <?php 
                    
                    the_post_thumbnail( array( 400, 400 ) ); 
                    ?>

                </div>
                <div class="image-md">
                    <?php 
                    the_post_thumbnail( array( 550, 550 ) ); 
                     
                    ?>

                </div>
                <div class="image-lg">
                    <?php 
                    the_post_thumbnail( array( 700, 700 ) ); 
                     
                    ?>

                </div>

                <div class="contacts">
                <?php 
                    echo "<h4>".$dep_address."</h4>"."<p>".get_post_meta($post->ID, 'address', true)."</p>";
                    echo "<h4>".$dep_phones."</h4>";
                    $contact_result = get_post_meta($post->ID, 'contacts', true);
                    $contact_data = preg_split( '/[^0-9:() +-]/ui' , $contact_result,-1,PREG_SPLIT_NO_EMPTY);
                    print_r($contact_data[2]);
                    echo "<br />";
                    print_r($contact_data[5]);
                    echo "<br />";
                    print_r($contact_data[8]);
                    echo "<br />";
                ?>
                
                <?php
                    $contact_result_2 = get_post_meta($post->ID, 'contacts', true); 
                    preg_match( '/([a-z0-9_\.\-])+\@(([a-z0-9\-])+\.)+([a-z0-9]{2,4})+/i' , $contact_result_2, $matches);
                    if (!empty($matches[0]))
                    { 
                      echo "<h4>Email:</h4>";
                      echo "<a href=''>".$matches[0]."</a>";
                    }
                ?>
                <br>
                <?php
                    echo "<h4>".$dep_hours."</h4>";
                    $work_result = get_post_meta($post->ID, 'working_hours', true);
                    $work_data = preg_split( '/[^а-яА-ЯёЁ0-9: -]/ui' , $work_result,-1,PREG_SPLIT_NO_EMPTY);     
                    ?>
                    <table class="working-hours"> 

                        <tr>
                          <td>
                              <?php 
                                print_r($work_data[1]); 
                              ?>
                          </td>  
                                <!-- echo ":&nbsp&nbsp";  -->
                          <td class="hours">
                              <?php 
                                print_r($work_data[3]);
                              ?>
                          </td>
                        </tr>
                        <tr>
                          <td>
                              <?php 
                                print_r($work_data[5]); 
                              ?>
                          </td>  
                                <!-- echo ":&nbsp&nbsp";  -->
                          <td class="hours">
                              <?php 
                                print_r($work_data[7]);
                              ?>
                          </td>
                        </tr>
                        <tr>
                          <td>
                              <?php 
                                print_r($work_data[9]); 
                              ?>
                          </td>  
                                <!-- echo ":&nbsp&nbsp";  -->
                          <td class="hours">
                              <?php 
                                print_r($work_data[11]);
                              ?>
                          </td>
                        </tr>
                 
                    </table>
                
            </div>
                


            </header>

            
            
            

            <div class="clear"></div>
            <div class="entry-content"><?php the_content(); ?></div>
                
            <div class="new-content">
                <?php
                  echo "<div class = 'services'>";
                    echo "<h4>".$dep_offer."</h4>";
                    $services_result = get_post_meta($post->ID, 'services', true);
                    $services_data = preg_split( '/[^a-zA-Zа-яА-ЯёЁіІїЇєЄ0-9 -]/ui' , $services_result,-1,PREG_SPLIT_NO_EMPTY);
                     
                         echo "<ul>";
                         for ($i = 0; $i < count($services_data); $i++)
                         {
                          if($services_data[$i] == 'title'){continue;}
                              if($services_data[$i+1] == 'items') {
                                  $services_data[$i+1] = '<br>';
                                  echo "</ul><ul>"; 
                                  echo "<li class = 'category'>";
                                  print_r($services_data[$i]); 
                                  echo "</li>";  
                                  continue;
                                }
                              
                              echo "<li class = 'service'>";
                              print_r($services_data[$i]);
                              echo "</li>";

                         } 
                        echo "</ul>";
                    echo "<br><br><br>";
                  echo "</div>"; 
                ?>

            </div>
        </article>
    <?php endwhile; ?>
    </div>
      <div class="clear"></div>

          <div class="maps">

                  <h4><?php echo $dep_map; ?></h4>
                  <?php $meta_values = get_post_meta($post->ID, 'coordinates', true);
                  $coordinates_data = preg_split( '/[^0-9() .+-]/ui' , $meta_values,-1,PREG_SPLIT_NO_EMPTY);
                  $lat = $coordinates_data[0];
                  $lng = $coordinates_data[1];
                  ?>
              <div id="map">
            
              </div>
              <script>
                function initMap() {
                  var latitude = parseFloat('<?php echo $lat ?>');
                  var longitude = parseFloat('<?php echo $lng ?>');
                     
                  var uluru = {lat: latitude, lng: longitude};
                  var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 18,
                    center: uluru
                  });
                  var marker = new google.maps.Marker({
                    position: uluru,
                    map: map
                  });
                }
              </script>
              <script async defer
              src="https://maps.googleapis.com/maps/api/js?key=AIzaSyByx6q61twWq8bjZyR78AndRVurzFpiqQQ&callback=initMap">
              </script>

        </div> 
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>