<?php

class tweetastic {
	
	/*  Tweetastic Variables
	***/
	
	var $username = '';
	var $password = '';
	var $user_agent = '';
	var $type = 'json';
	var $headers = array('Expect:', 'X-Twitter-Client: ','X-Twitter-Client-Version: ','X-Twitter-Client-URL: ');
	var $responseInfo=array();
	var $suppress_response_code = false;
	var $debug = true;
	
	
	
	/*  Search API Methods
	***/
	
	// Later
	
	
	
	/*  REST API Methods
	***/
	
	/*  Timeline Methods */
	
	function statusesPublicTimeline() {
	
	    if( !in_array( $this->type, array( 'xml','json','rss','atom' ) ) )
	        return false;
	        
        $request = 'http://twitter.com/statuses/public_timeline.' . $this->type . $qs;
		
		return $this->objectify( $this->process( $request ) );
	}
	
	function statusesFriendsTimeline( $since_id = false, $max_id = false, $count = false, $page = false ) {
	
	    if( !in_array( $this->type, array( 'xml','json','rss','atom' ) ) )
	        return false;
	    
	    $args = array();
	    if( $since_id )
	        $args['since_id'] = (int) $since_id;
	    if( $max_id )
	        $args['max_id'] = (int) $max_id;
	    if( $count )
	        $args['count'] = (int) $count;
	    if( $page )
	        $args['page'] = (int) $page;
	    
	    $qs = '';
	    if( !empty( $args ) )
	        $qs = $this->_glue( $args );
	    
	    $request = 'http://twitter.com/statuses/friends_timeline.' . $this->type . $qs;
	    
	    return $this->objectify( $this->process( $request ) );
	}
	
	function statusesUserTimeline( $id = false, $count = 20, $since = false, $since_id = false, $page = false) {
	
	    if( !in_array( $this->type, array( 'xml','json','rss','atom' ) ) )
	        return false;
	    
	    $args = array();
	    if( $id )
	        $args['id'] = $id;
	    if( $count )
	        $args['count'] = (int) $count;
	    if( $since )
	        $args['since'] = (string) $since;
	    if( $since_id )
	        $args['since_id'] = (int) $since_id;
	    if( $page )
	        $args['page'] = (int) $page;
	    
	    $qs = '';
	    if( !empty( $args ) )
	        $qs = $this->_glue( $args );
			            
        if( $id === false )
            $request = 'http://twitter.com/statuses/user_timeline.' . $this->type . $qs;
        else
            $request = 'http://twitter.com/statuses/user_timeline/' . rawurlencode($id) . '.' . $this->type . $qs;
        
		return $this->objectify( $this->process( $request ) );
	}
	
	function statusesMentions( $page = false, $since = false, $since_id = false ) {
	
	    if( !in_array( $this->type, array( 'xml','json','rss','atom' ) ) )
	        return false;
	        
	    $args = array();
	    if( $page )
	        $args['page'] = (int) $page;
	    if( $since )
	        $args['since'] = (string) $since;
	    if( $since_id )
	        $args['since_id'] = (int) $since_id;
	    
	    $qs = '';
	    if( !empty( $args ) )
	        $qs = $this->_glue( $args );
	    
	    $request = 'http://twitter.com/statuses/replies.' . $this->type . $qs;
	    
	    return $this->objectify( $this->process( $request ) );
	}
	
	
	/* Status Methods */
	
	function statusesShow( $id ) {
	
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
        $request = 'http://twitter.com/statuses/show/'.intval($id) . '.' . $this->type;
        
		return $this->objectify( $this->process( $request ) );
    }
	
	function statusesUpdate( $status, $replying_to = false ) {
	
		if( !in_array( $this->type, array( 'xml','json' ) ) )
			return false;
			
        $request = 'http://twitter.com/statuses/update.' . $this->type;
		//$status = $this->shorturl($status);
        $postargs = array( 'status' => $status );
        if( $replying_to )
            $postargs['in_reply_to_status_id'] = (int) $replying_to; 

		return $this->objectify( $this->process( $request, $postargs ) );
	}
	
	function statusesDestroy( $id ) {
	
        if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
        $request = 'http://twitter.com/statuses/destroy/' . (int) $id . '.' . $this->type;
        
        return $this->objectify( $this->process( $request, true ) );
    }
    
	
	/* User Methods */
	
	function usersShow( $id, $email = false, $user_id = false, $screen_name = false ) {
	
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
	    if( $user_id ) :
	        $qs = '?user_id=' . (int) $user_id;
	    elseif ( $screen_name ) :
	        $qs = '?screen_name=' . (string) $screen_name;
	    elseif ( $email ) :
	        $qs = '?email=' . (string) $email;
	    else :
	        $qs = (int) $id;
	    endif;
	    
        $request = 'http://twitter.com/users/show/' . $qs . $this->type;
        
		return $this->objectify( $this->process( $request ) );
	}
	
	function statusesFriends( $id = false, $page = false ) {
	
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
        $args = array();
	    if( $id )
	        $args['id'] = $page;
	    if( $page )
	        $args['page'] = (int) $page;
	    
	    $qs = '';
	    if( !empty( $args ) )
	        $qs = $this->_glue( $args );
	        
	    $request = ( $id ) ? 'http://twitter.com/statuses/friends/' . $id . '.' . $this->type . $qs : 'http://twitter.com/statuses/friends.' . $this->type . $qs;
	    
		return $this->objectify( $this->process( $request ) );
	}
	
	function statusesFollowers( $page = false ) {
	
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
        $request = 'http://twitter.com/statuses/followers.' . $this->type;
        if( $page )
            $request .= '?page=' . (int) $page;
        
		return $this->objectify( $this->process( $request ) );
	}
	
	
	/* Direct Message Methods */
	
	function directMessages( $since = false, $count = null, $since_id = false, $page = false ) {
		
		if( !in_array( $this->type, array( 'xml','json','rss','atom' ) ) )
			return false;
			
        $qs='?';
        $qsparams = array();
        if( $since !== false )
            $qsparams['since'] = rawurlencode($since);
        if( $since_id )
            $qsparams['since_id'] = (int) $since_id;
        if( $page )
            $qsparams['page'] = (int) $page;
            
        $request = 'http://twitter.com/direct_messages.' . $this->type . implode( '&', $qsparams );

		return $this->objectify( $this->process( $request ) );
	}
	
	function directMessagesSent( $since = false, $since_id = false, $page = false ) {
	
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
			return false;
			
	    $qs = '?';
	    $qsparams = array();
	    if( $since !== false )
            $qsparams['since'] = rawurlencode($since);
        if( $since_id )
            $qsparams['since_id'] = (int) $since_id;
        if( $page )
            $qsparams['page'] = (int) $page;
            
        $request = 'http://twitter.com/direct_messages/sent.' . $this->type . implode( '&', $qsparams );
        
        return $this->objectify( $this->process( $request ) );
	}
	
	function directMessagesNew( $user, $text ) {
	
		if( !in_array( $this->type, array( 'xml','json' ) ) )
			return false;
			
        $request = 'http://twitter.com/direct_messages/new.' . $this->type;
        $postargs = 'user=' . rawurlencode($user) . '&text=' . rawurlencode($text);

		return $this->objectify( $this->process( $request, $postargs ) );
	}
	
	function directMessageDestroy( $id ) {
	
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
	    $request = 'http://twitter.com/direct_messages/destroy/' . (int) $id . '.' . $this->type;
	    
	    return $this->objectify( $this->process( $request ) );
	}
	
	
	/* Friendship Methods */
	
	function friendshipsCreate( $id, $notifications = false ) {
	
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
		$request = 'http://twitter.com/friendships/create/' . $id . '.' . $this->type;
		if( $notifications )
		    $request .= '?follow=true';
		    
		return $this->objectify( $this->process( $request, true ) );
	}
	
	function friendshipsDestroy( $id ) {
	
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
		$request = 'http://twitter.com/friendships/destroy/' . $id . '.' . $this->type;
		
		return $this->objectify( $this->process( $request, true ) );
	}
	
	function friendshipsExists( $user_a, $user_b ) {
	
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
		$qs = '?user_a=' . rawurlencode( $user_a ) . '&amp;' . rawurlencode( $user_b );
		$request = 'http://twitter.com/friendships/exists.' . $this->type . $qs;
		
		return $this->objectify( $this->process( $request ) );
	}
	
	
	/* Social Graph Methods */
	
	function friendsIds( $id = false) {
	/* @todo same as friends()? */
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
	    $request = 'http://twitter.com/friends/ids';
	    if( $id )
	        $request .= '/' . (int) $id . '.' . $this->type;
	        
	    return $this->objectify( $this->process( $request ) );
	}
	
	function followersIds( $id = false ) {
	/* @todo same as followers() */
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
	    $request = 'http://twitter.com/followers/ids';
	    if( $id )
	        $request .= '/' . (int) $id . '.' . $this->type;
	        
	    return $this->objectify( $this->process( $request ) );
	}
	
	
	/* Account Methods */
	
	function accountVerifyCredentials() {
		
	}
	
	function accountRateLimitStatus() {
	
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
		$request = 'http://twitter.com/account/rate_limit_status.' . $this->type;
		
		return $this->objectify( $this->process( $request ) ); // was ( $out )
	}
	
	function accountEndSession() {
		
	}
	
	function accountUpdateDeliveryDevice( $device ) {
	
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
		if( !in_array( $device, array('im','sms','none') ) )
			return false;
			
		$qs = '?device=' . $device;
		$request = 'http://twitter.com/account/update_delivery_device.' . $this->type . $qs;
		
		return $this->objectify( $this->process( $request ) );
	}
	
	function accountUpdateProfileColors( $colors = array() ) {
	
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
	    $postdata = array();
	    foreach( $colors as $ck => $cv ) :
	        if( preg_match('/^(?:(?:[a-f\d]{3}){1,2})$/i', $hex) ) :
                $postdata[$ck] = (string) $cv;
            endif;
	    endforeach;
	    
		$request = 'http://twitter.com/account/update_profile_colors.' . $this->type;
		
	    return $this->objectify( $this->process( $request, $postdata ) );
	}
	
	function accountUpdateProfileImage( $file ) {
	
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
	    // Adding @ ensures the POST will be raw multipart data encoded. This MUST be a file, not a URL. Handle it outside of the class.
	    $postdata = array( 'image' => "@$file");
	    
	    $request = 'http://twitter.com/account/update_profile_image.' . $this->type;
	    
	    return $this->objectify( $this->process( $request, $postdata ) );
	}
	
	function accountUpdateBackgroundImage( $file ) {
	
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
	    // Adding @ ensures the POST will be raw multipart data encoded. This MUST be a file, not a URL. Handle it outside of the class.
	    $postdata = array( 'image' => "@$file");
	    $request = 'http://twitter.com/account/update_profile_background_image.' . $this->type;
	    
	    return $this->objectify( $this->process( $request, $postdata ) );
	}
	
	function accountUpdateProfile( $fields = array() ) {
	
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
	    $postdata = array();
	    foreach( $fields as $pk => $pv ) :
	        switch( $pk ) {
	            case 'name' :
	                $postdata[$pk] = (string) substr( $pv, 0, 20 );
	                break;
	            case 'email' :
	                if( preg_match( '/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $pv ) )
	                    $postdata[$pk] = (string) $pv;
	                break;
	            case 'url' :
	                $postdata[$pk] = (string) substr( $pv, 0, 100 );
	                break;
	            case 'location' :
	                $postdata[$pk] = (string) substr( $pv, 0, 30 );
	                break;
	            case 'description' :
	                $postdata[$pk] = (string) substr( $pv, 0, 160 );
	                break;
	            default :
	                break;
	        }
	        
	    endforeach;
	    
	    $request = 'http://twitter.com/account/update_profile.' . $this->type;
	    
	    return $this->objectify( $this->process( $request, $postdata ) );
	}
	
	
	/* Favourite Methods */
	
	function favorites( $id, $page=false ) {
	
	    if( !in_array( $this->type, array( 'xml','json','rss','atom' ) ) )
	        return false;
	        
		if( $page != false )
			$qs = '?page=' . $page;
		
		$request = 'http://twitter.com/favorites.' . $this->type . $qs; 
		
		return $this->objectify( $this->process( $request ) );
	}	
	
	function favoritesCreate( $id ) {
	
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
		$request = 'http://twitter.com/favorites/create/' . $id . '.' . $this->type;
		
		return $this->objectify( $this->process( $request ) );
	}
	
	function favoritesDestroy( $id ) {
	
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
		$request = 'http://twitter.com/favorites/destroy/' . $id . '.' . $this->type;
		
		return $this->objectify( $this->process( $request ) );	
	}
	
		
	/* Notification Methods */
	
	function notificationsFollow() {
	
	}
	
	function notificationsLeave() {
		
	}
	
	
	/* Block Methods */
	
	function blocksCreate( $id ) {
	
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
		$request = 'http://twitter.com/blocks/create/' . $id . '.' . $this->type;
		
		return $this->objectify( $this->process( $request ) );
	}
	
	function blocksDestroy() {
	
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
		$request = 'http://twitter.com/blocks/destroy/' . $id . '.' . $this->type;
		
		return $this->objectify( $this->process( $request ) );
	}
	
	function blocksExists() {
		
	}
	
	function blocksBlocking() {
		
	}
	
	function blocksIds() {
		
	}
	
	
	/* Help Methods */
	
	function helpTest() {
	
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
		$request = 'http://twitter.com/help/test.' . $this->type;
		
		if( $this->objectify( $this->process( $request ) ) == 'ok' )
			return true;
		
		return false;
	}
	
	
	
	/*  Tweetastic Methods
	***/

	function process( $url, $postargs = false ) {
		
		// if set true, add $suppress=true to end of url
	    $url = ( $this->suppress_response_code ) ? $url . '&suppress_response_code=true' : $url;
	    
	    // initialise $ch as curl session, with $url set
		$ch = curl_init($url);
		
		// if $postargs 
		//	HTTP POST
		//	HTTP POST post data
		if($postargs !== false) {
			curl_setopt ($ch, CURLOPT_POST, true);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $postargs);
        }
        
        // build credentials (required or not)
		if($this->username !== false && $this->password !== false)
			curl_setopt($ch, CURLOPT_USERPWD, $this->username.':'.$this->password );
        
        // return as string, do not output
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // unnecessary,  output verbose info (and 1 must equals true)
//        curl_setopt($ch, CURLOPT_VERBOSE, true);
        
        // unnecessary, exclude body and make request method HEAD
//        curl_setopt($ch, CURLOPT_NOBODY, 0);
        
        // include HEADER in the output for debug
        if( $this->debug ) :
            curl_setopt($ch, CURLOPT_HEADER, true);
        else :
            curl_setopt($ch, CURLOPT_HEADER, false);
        endif;
        
        // unnecessary, contents set in globals included
//        curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
        
        // unnecessary
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        
        // as set in globals (prob something expected)
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        
        // execute the given curl session
        $response = curl_exec($ch);
        
        // info about last transfer, as array as non specific
        $this->responseInfo = curl_getinfo($ch);
        
        // close
        curl_close( $ch );
        
        // debug info, if global set to true
        if( $this->debug ) :
            $debug = preg_split("#\n\s*\n|\r\n\s*\r\n#m", $response);
            echo'<pre>' . $debug[0] . '</pre>'; exit;
        endif;
        
        // if 200 OK response, else nout
        if( intval( $this->responseInfo['http_code'] ) == 200 )
			return $response;    
        else
            return false;
	}
	
	function processOld( $url, $postargs = false ) {
	
	    $url = ( $this->suppress_response_code ) ? $url . '&suppress_response_code=true' : $url;
		$ch = curl_init($url);
		
		if($postargs !== false) {
			curl_setopt ($ch, CURLOPT_POST, true);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $postargs );
        }
        
		if($this->username !== false && $this->password !== false)
			curl_setopt($ch, CURLOPT_USERPWD, $this->username.':'.$this->password );
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        
        if( $this->debug ) :
            curl_setopt($ch, CURLOPT_HEADER, true);
        else :
            curl_setopt($ch, CURLOPT_HEADER, false);
        endif;
        
        curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);

        $response = curl_exec( $ch );
        
        $this->responseInfo = curl_getinfo($ch);
        curl_close( $ch );
        
        if( $this->debug ) :
            $debug = preg_split("#\n\s*\n|\r\n\s*\r\n#m", $response);
            echo'<pre>' . $debug[0] . '</pre>'; exit;
        endif;
        
        if( intval( $this->responseInfo['http_code'] ) == 200 )
			return $response;    
        else
            return false;
	}
	
	function objectify( $data ) {
	
		if( $this->type ==  'json' )
			return json_decode( $data );

		else if( $this->type == 'xml' ) {
		
			if( function_exists('simplexml_load_string') ) :
			    $obj = simplexml_load_string( $data );			        
			endif;
			return $obj;
		}
		
		else
			return false;
	}
	
	function _glue( $array ) {
	
	    $query_string = '';
	    
	    foreach( $array as $key => $val ) :
	        $query_string .= $key . '=' . rawurlencode( $val ) . '&';
	    endforeach;
	    
	    return '?' . substr( $query_string, 0, strlen( $query_string )-1 );
	}
	
}

?>