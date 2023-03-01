<?php namespace Sourcebit\Dprimecms\Repositories;


interface AuthInterface
{
    
    public function roleRedirect();
    
    public function register(array $credentials);
    
    public function createRole(array $credentials);

    public function authenticate(array $credentials);

    public function authenticateAndRemember(array $credentials);
   
    public function getUser();

    // @return user data model
    public function getAllUser();
    
    public function findById($id);

    public function findRoleByID($id);

    public function findRoleByName($name);

    public function findUserById($id);
    
    public function check();

    // @return bool
    public function userExist($id); 

    public function logout();

    public function registerAndActivate(array $credentials);

    public function update($user, array $credentials);

    public function disableCheckpoints();

    //permission
    public function hasAnyAccess($routes);
    public function hasAccess($routes);

    //activation
    public function createActivation($userInfo);
    public function completeActivation($userInfo, $code);
    public function removeExpiredActivation();
    public function activateDeactivate($userId, $status);       

    //reminder
    public function reminder($userInfo);
    public function resetPassword($user, $code, $newPassword);
    public function removeExpired();


    public function guest();


}