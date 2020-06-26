<?php
    namespace INJQEW;
    use Vcoud\Mercadolibre\Meli;
          
    class ControllerMercadolibre
    {
    
        public function __construct()
        {
    
        }

        static function login()
        {

            $meli = new Meli('8593943822521310', 'KOZgGKyKQQItbBJ0XBpWOntdukpZd6tq');

            $redirectURI = get_site_url().'/wp-admin/admin.php?page=my-custom-page';

            
                

                if($_GET['code'] && $_GET['publish_item']) {

                    // If code exist and session is empty
                    if($_GET['code'] && !($_SESSION['access_token'])) {
                        // If the code was in get parameter we authorize
                        $user = $meli->authorize($_GET['code'], $redirectURI);

                        // Now we create the sessions with the authenticated user
                        $_SESSION['access_token'] = $user['body']->access_token;
                        $_SESSION['expires_in'] = time() + $user['body']->expires_in;
                        $_SESSION['refresh_token'] = $user['body']->refresh_token;
                    } else {
                        // We can check if the access token in invalid checking the time
                        if($_SESSION['expires_in'] < time()) {
                            try {
                                // Make the refresh proccess
                                $refresh = $meli->refreshAccessToken();

                                // Now we create the sessions with the new parameters
                                $_SESSION['access_token'] = $refresh['body']->access_token;
                                $_SESSION['expires_in'] = time() + $refresh['body']->expires_in;
                                $_SESSION['refresh_token'] = $refresh['body']->refresh_token;
                            } catch (Exception $e) {
                                echo "Exception: ",  $e->getMessage(), "\n";
                            }
                        }
                    }

                    // We construct the item to POST
                    $item = array(
                        "title" => "hermosa joya para regalar en el dia del padre",
                        "category_id" => "MLA1438",
                        "price" => 10000,
                        "currency_id" => "ARS",
                        "available_quantity" => 1,
                        "buying_mode" => "buy_it_now",
                        "listing_type_id" => "free",
                        "condition" => "new",
                        "description" => "Articulo de alta calidad de la mas prestigiosa marca de joyas",
                        "video_id" => "RXWn6kftTHY",
                        "pictures" => array(
                                            array(
                                                    "source" => "https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcTAKoubLppPBpuDlI1x3uqonmiSSpqKuQp9cA&usqp=CAU"),
                                            array(
                                                    "source" => "https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcTAKoubLppPBpuDlI1x3uqonmiSSpqKuQp9cA&usqp=CAU")
                                            )
                                 );
                    
                    $response = $meli->post('/items', $item, array('access_token' => $_SESSION['access_token']));

                    // We call the post request to list a item
                    echo "<h4>Response</h4>";
                    //print_r ($response);
                    

                    echo "<h4>Felicitaciones! su articulo ya se encuentra publicado</h4>";
                    echo "<p>Vaya al enlace permanente para ver cómo se ve en nuestro sitio.</p>";
                    echo '<a target="_blank" href="'.$response["body"]->permalink.'">'.$response["body"]->permalink.'</a><br />';

                } else if($_GET['code']) {
                    echo '<p><a alt="Publish Item" class="btn" href="'.get_site_url().'/wp-admin/admin.php?page=my-custom-page&code='.$_GET['code'].'&publish_item=ok">Publish Item</a></p>';
                } else {
                    echo '<a href="' . $meli->getAuthUrl($redirectURI, Meli::$AUTH_URL['MLA']) . '">Login para Mercadolibre</a>';
                }
            
        }
    }