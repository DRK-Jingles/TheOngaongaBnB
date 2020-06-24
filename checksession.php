<?php
 session_start();
 define('AC_ADMIN',9);
 define('AC_MANAGER',8);
 define('AC_AUTHENTICATED',4);
 define('AC_GUEST',0);
 //function to check if the user is logged else send to the login page 
 function checkUser($acl = 0) {
     $role = $_SESSION['role'];
     
     //test the role for if it is less than the logged in user.
     //log out the user if it is not - just a simple exit
     if ($acl < $role)
        logout();
    
     $_SESSION['URI'] = '';    
     if ($_SESSION['loggedin'] == 1)
        return TRUE;
     else {
        $_SESSION['URI'] = 'http://localhost'.$_SERVER['REQUEST_URI']; //save current url for redirect     
        header('Location: http://localhost/bit608/login.php', true, 303);       
     }       
 }
 //just to show we are logged in
 function loginStatus() {
     $un = $_SESSION['username'];
     if ($_SESSION['loggedin'] == 1)     
         echo "<h2-->Logged in as $un";
     else
         echo "<h2>Logged out</h2>";            
 }
 //log a user in
 function login($id,$username,$role) {
    //simple redirect if a user tries to access a page they have not logged in to
    if ($_SESSION['loggedin'] == 0 and !empty($_SESSION['URI']))        
         $uri = $_SESSION['URI'];          
    else { 
      $_SESSION['URI'] =  'http://localhost/bit608/listmembers.php';         
      $uri = $_SESSION['URI'];           
    }  
    
    $_SESSION['role'] = $role;   
    $_SESSION['loggedin'] = 1;        
    $_SESSION['userid'] = $id;   
    $_SESSION['username'] = $username; 
    $_SESSION['URI'] = ''; 
    header('Location: '.$uri, true, 303);        
 }
 //simple logout function
 function logout(){
   $_SESSION['role'] = 0;
   $_SESSION['loggedin'] = 0;
   $_SESSION['userid'] = -1;        
   $_SESSION['username'] = '';
   $_SESSION['URI'] = '';
   header('Location: http://localhost/bit608/login.php', true, 303);    
 }
 ?>