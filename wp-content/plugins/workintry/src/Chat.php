<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
require_once ('../../../../wp-config.php');
require ABSPATH . '/wp-load.php' ;
global $wpdb;
class Chat implements MessageComponentInterface {
    protected $clients;
    protected $users;
    public function __construct() {   
        $this->clients = new \SplObjectStorage;    
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);              
        echo 'started server and new conenction is there';
        echo "New connection! ({$conn->resourceId})\n";       
    }   

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;        
        global $current_user;
        $mess = json_decode($msg, true);
        $usersid = sanitize_text_field($mess['user_id']);        
        if( $mess['type'] == 'login' ){            
            $this->users[$usersid] = $from;
            //Update user login
            global $wpdb;
            $last_seen_value = 'cl_last_seen';
            $set_online_status = 'online';
            $usermeta_table_name = $wpdb->prefix . 'usermeta';
            $user_id = sanitize_text_field($mess['user_id']);
            $wpdb->query($wpdb->prepare("UPDATE $usermeta_table_name 
                SET meta_value = %s
                WHERE user_id = %s
                AND meta_key = %s                    
            ", $set_online_status, $user_id, $last_seen_value) );             
            foreach ($this->clients as $client) {               
                if ($from === $client) {
                    // The sender is not the receiver, send to each client connected                
                    $data['uid'] = $from->resourceId;
                    $data['userid'] = $mess['user_id'];
                    $data['type'] = $mess['type'];
                    $msgs = json_encode( $data );
                    $client->send($msgs);   
                }

                //Send to all now
                foreach ($this->clients as $client) {
                  if ($from === $client) {
                    //Don't send now
                  } else {
                    $data['uid'] = $from->resourceId;
                    $data['userid'] = $mess['user_id'];
                    $data['type'] = $mess['type'];
                    $msgs = json_encode( $data );
                    $client->send($msgs);
                  }
                }                
              }
        }elseif( $mess['type'] == 'typing' ){
              //Send typing notification only
              global $current_user, $wpdb;
              $data = json_decode( $msg, true );
              $mess = json_decode($msg, true);
              $usersid = $mess['user_id'];
              $to_user_id = $data['to'];
              foreach ($this->clients as $client) {                
                    $data['image'] = $image;
                    $data['count'] = $count;
                    $msgs = json_encode( $data );
                    
                    $receivers = (object) $this->users[$to_user_id];
                     if( $client === $receivers){
                        //Send only to this one
                        $client->send($msgs);
                     }     
                }
        }elseif( $mess['type'] == 'typingstop' ){
          //Send typing notification only
              global $current_user, $wpdb;
              $data = json_decode( $msg, true );
              $mess = json_decode($msg, true);
              $usersid = $mess['user_id'];
              $to_user_id = $data['to'];
              foreach ($this->clients as $client) {                
                    $data['image'] = $image;
                    $data['count'] = $count;
                    $msgs = json_encode( $data );               
                    $receivers = (object) $this->users[$to_user_id];
                     if( $client === $receivers){
                        //Send only to this one
                        $client->send($msgs);
                     } 
                }
        } else {
            global $current_user, $wpdb;
            $data = json_decode( $msg, true );            
            $image = '';
            $regex = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
              preg_match_all(
              $regex,
               $data['msg'],
              $macth

            );
            $new_string = $macth[0];
            $strings = array();
            $strings_savable = array();
            $old_message = '';
            if( is_array( $new_string ) ){
              foreach ( $new_string as $key => $value ) {
                //Creating links html array
                $value = trim($value);
                $strings[] = '<a href="'.esc_url($value).'">'.esc_url($value).'</a> ';
              }
            }
            //Replacing found URL strings with their proper html markup
            $my_values = '<p>';
            $my_values .= str_replace($new_string, $strings, $data['msg'] );
            $my_values .= '</p>';
            //Now make savable string of links
            if( is_array( $new_string ) ){
              foreach ( $new_string as $key => $value ) {
                //Creating links html array
                $strings_savable[] = '[link src="'.esc_url($value).'"]';
              }
            }
            $my_savable_values = str_replace($new_string, $strings_savable, $data['msg'] );
            
            $images_to_save = array();
            if( empty( $data['imagesIds'] ) ){                
                $images_to_save = 'NULL';
            } else {
              $images_to_save['images'] = $data['imagesIds'];
              $images_to_save = json_encode( $images_to_save );
            }
            //LINK ONLY
            $chat_table_name = $wpdb->prefix . 'chat_message'; 
            $to_user_id = $data['to'];
            $from_user_id = $data['from'];
            $chat_message = $data['msg'];           
            $timeLine = time();            
            $status       = '1';
            $wpdb->insert( 
                $chat_table_name, 
                array( 
                    'to_user_id' => $to_user_id, 
                    'from_user_id'  => $from_user_id,
                    'chat_message' => $my_savable_values,
                    'message_time' =>  $timeLine,
                    'status' => $status,
                    'chat_files' =>  $images_to_save
                )          
            ); 

            //Check for the images 
            $images_IDS = $data['imagesIds'];
            $images_URLs = $data['imagesUrls'];
            if( is_array( $images_URLs ) ){              
              foreach ( $images_URLs as $key => $image) {
                $nyimg .= '<figure class="item" data-src="'. esc_url($image) .'"><img src="'. esc_url($image) .'" alt=""></figure>';
              }              
            }
            $my_values .= $nyimg;
            
            //Now get total unseen count for the receiver and update its value as well
            $receiver_messages =
              $wpdb->get_results($wpdb->prepare( "SELECT * from $chat_table_name WHERE to_user_id = %s AND status = '1' ", $to_user_id ) ); 
              $count = '';
            if( !empty( $receiver_messages ) ){
              $count = count( $receiver_messages );
            }              

            //appended to data above
            foreach ($this->clients as $client) {                
                $data['image'] = $image;
                $data['count'] = $count;
                $data['msg'] = $my_values;
                $data['time'] = time();
                $msgs = json_encode( $data );                
                $receivers = (object) $this->users[$to_user_id];
                 if( $client === $receivers){                    
                    $client->send($msgs);
                 }elseif( $client !== $receivers ){
                    //
                 }
                if( $client === $from ){
                    $client->send($msgs);
                }
                    
            }
        }      
    }

    public function onClose(ConnectionInterface $conn) {
      global $wpdb;
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        $timeLine = time();       
        $keys = array_search($conn, $this->users);
        unset( $this->users[$keys] );              
        foreach ($this->clients as $client) {                      
            $data['uid'] = $keys;
            $data['userid'] = $keys;
            $data['type'] = 'logout';
            $data['time'] = $timeLine;
            $msgs = json_encode( $data );
            $client->send($msgs);              
        }              
        //Also update this users last seen in db
        //We got last seen time to current time above
        $usermeta_table_name = $wpdb->prefix . 'usermeta';
        $wpdb->query($wpdb->prepare("UPDATE $usermeta_table_name 
            SET meta_value = %s 
            WHERE user_id = $keys
            AND meta_key = 'cl_last_seen'                    
            ", $timeLine) );                    
        echo "Connection {$conn->resourceId} has disconnected\n";       
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}