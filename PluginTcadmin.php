<?php
require_once 'modules/admin/models/ServerPlugin.php';

require_once 'library/CE/NE_MailGateway.php';
require_once 'library/CE/NE_Network.php';

/**
* @package Plugins
*/
class PluginTcadmin extends ServerPlugin
{
    public $features = array(
        'packageName' => false,
        'testConnection' => false,
        'showNameservers' => false
    );

    /*****************************************************************/
    // function getVariables - required function
    /*****************************************************************/

    function getVariables(){
        /* Specification
            itemkey     - used to identify variable in your other functions
            type        - text,textarea,yesno,password
            description - description of the variable, displayed in ClientExec
            encryptable - used to indicate the variable's value must be encrypted in the database
        */


        $variables = array (
        /*T*/"Name"/*/T*/ => array (
                                    "type"            =>    "hidden",
                                    "description"     =>    /*T*/"Used By CE to show plugin - must match how you call the action function names"/*/T*/,
                                    "value"           =>    "Tcadmin"
                            ),
        /*T*/"Billing API url"/*/T*/ => array (
                                    "type"            =>    "text",
                                    "description"     =>    /*T*/"Example http://panel.stealthservers.net/billingapi.aspx"/*/T*/,
                                    "value"           =>    ""
                            ),
        /*T*/"Admin username"/*/T*/ => array (
                                    "type"            =>    "text",
                                    "description"     =>    /*T*/"Administrator username"/*/T*/,
                                    "value"           =>    ""
                            ),
        /*T*/"Admin password"/*/T*/ => array (
                                    "type"            =>    "password",
                                    "description"     =>    /*T*/"Administrator password"/*/T*/,
                                    "value"           =>    "",
                                    "encryptable"     => true
                            ),
        /*T*/"Error Email"/*/T*/ => array (
                                    "type"            =>    "text",
                                    "description"     =>    /*T*/"Email errors will be sent to."/*/T*/,
                                    "value"           =>    ""
                            ),
        /*T*/"Description"/*/T*/ => array (
                                    "type"            =>    "hidden",
                                    "description"     =>    /*T*/"Description visable by admin in server settings"/*/T*/,
                                    "value"           =>    /*T*/"Tcadmin configuration"/*/T*/
                            ),
        /*T*/"Client Username Custom Field"/*/T*/ => array(
                                    "type"            =>    "text",
                                    "description"     => /*T*/"Enter the name of the package custom field that will hold the client username."/*/T*/,
                                    "value"           => ""
                            ),

        /*T*/"Client Password Custom Field"/*/T*/ => array(
                                    "type"            => "text",
                                    "description"     => /*T*/"Enter the name of the package custom field that will hold the client password"/*/T*/,
                                    "value"           => ""
                            ),
        /*T*/"Location Custom Field"/*/T*/ => array(
                                    "type"            =>    "text",
                                    "description"     => /*T*/"Enter the name of the package custom field that will hold the locations.<br> Note: Locations should look like so: North (Tcadmin DCID),East (Tcadmin DCID),South (Tcadmin DCID),West (Tcadmin DCID)."/*/T*/,
                                    "value"           => ""
                            ),
        /*T*/"Game Hostname Custom Field"/*/T*/ => array(
                                    "type"            =>    "text",
                                    "description"     => /*T*/"Enter the name of the package custom field that will hold the game hostname. (optional)"/*/T*/,
                                    "value"           => ""
                            ),
        /*T*/"Game Rcon Password Custom Field"/*/T*/ => array(
                                    "type"            =>    "text",
                                    "description"     => /*T*/"Enter the name of the package custom field that will hold the game rcon password. (optional)"/*/T*/,
                                    "value"           => ""
                            ),
        /*T*/"Game Private Password Custom Field"/*/T*/ => array(
                                    "type"            =>    "text",
                                    "description"     => /*T*/"Enter the name of the package custom field that will hold the game private password. (optional)"/*/T*/,
                                    "value"           => ""
                            ),
        /*T*/"Voice Location Custom Field"/*/T*/ => array(
                                    "type"            =>    "text",
                                    "description"     => /*T*/"Enter the name of the package custom field that will hold the voice locations.<br> Note: Voice Locations should look like so: North (Tcadmin DCID),East (Tcadmin DCID),South (Tcadmin DCID),West (Tcadmin DCID)."/*/T*/,
                                    "value"           => ""
                            ),
        /*T*/"Voice Hostname Custom Field"/*/T*/ => array(
                                    "type"            =>    "text",
                                    "description"     => /*T*/"Enter the name of the package custom field that will hold the voice hostname. (optional)"/*/T*/,
                                    "value"           => ""
                            ),
        /*T*/"Voice Rcon Password Custom Field"/*/T*/ => array(
                                    "type"            =>    "text",
                                    "description"     => /*T*/"Enter the name of the package custom field that will hold the voice rcon password. (optional)"/*/T*/,
                                    "value"           => ""
                            ),
        /*T*/"Voice Private Password Custom Field"/*/T*/ => array(
                                    "type"            =>    "text",
                                    "description"     => /*T*/"Enter the name of the package custom field that will hold the voice private password.  (optional)"/*/T*/,
                                    "value"           => ""
                            ),
        /*T*/'package_vars'/*/T*/  => array(
                                    'type'            => 'hidden',
                                    'description'     => /*T*/'Whether package settings are set'/*/T*/,
                                    'value'           => '0',
                            ),
        /*T*/'package_vars_values'/*/T*/ => array(
                                    'type'            => 'hidden',
                                    'description'     => /*T*/'Tcadmin settings'/*/T*/,
                                    'value'           => array(
                                                                /*T*/'skip_setup_page'/*/T*/ => array(
                                                                                'type'            =>'yesno',
                                                                                'label'           => /*T*/'skip_setup_page'/*/T*/,
                                                                                'description'     => /*T*/'Skips the setup page email. <b>Values</b>: 1=Yes, 0=No'/*/T*/,
                                                                                'value'           => '0',
                                                                        ),
                                                                'game_datacenter' => array(
                                                                                'type'            => 'text',
                                                                                'label'           => /*T*/'game_datacenter'/*/T*/,
                                                                                'description'     => /*T*/'The datacenter id where the game server will be created. This is only used if skip_setup_page = 1'/*/T*/,
                                                                                'value'           => '',
                                                                        ),
                                                                'game_hostname' => array(
                                                                                'type'            => 'text',
                                                                                'label'           => /*T*/'game_hostname'/*/T*/,
                                                                                'description'     => /*T*/'The game server\'s hostname. This is only used if skip_setup_page = 1'/*/T*/,
                                                                                'value'           => '',
                                                                        ),
                                                                'game_rcon_password' => array (
                                                                                'type'            => 'text',
                                                                                'label'           => /*T*/'game_rcon_password'/*/T*/,
                                                                                'description'     => /*T*/'The game server\'s rcon password. This is only used if skip_setup_page = 1'/*/T*/,
                                                                                'value'           => '',
                                                                        ),
                                                                'game_private_password' => array (
                                                                                'type'            => 'text',
                                                                                'label'           => /*T*/'game_private_password'/*/T*/,
                                                                                'description'     => /*T*/'The game server\'s private password. This is only used if <b>skip_setup_page</b> = 1 and <b>game_private</b> = 1'/*/T*/,
                                                                                'value'           => '',
                                                                        ),
                                                                'game_id' => array (
                                                                                'type'            => 'text',
                                                                                'label'           => /*T*/'game_id'/*/T*/,
                                                                                'description'     => /*T*/'The game id of the new game sever. Set to blank or NONE to disable.'/*/T*/,
                                                                                'value'           => '',
                                                                        ),
                                                                'game_slots' => array (
                                                                                'type'            => 'text',
                                                                                'label'           => /*T*/'game_slots'/*/T*/,
                                                                                'description'     => /*T*/'The slots that the game server will have. If set to 0 or blank, no game server will be created.<br /> This variable can also contain the prefixes PRI and PUB, to specify private or public servers without having to use game_private.'/*/T*/,
                                                                                'value'           => '',
                                                                        ),
                                                                'game_add_slots' => array (
                                                                                'type'            => 'text',
                                                                                'label'           => /*T*/'game_add_slots'/*/T*/,
                                                                                'description'     => /*T*/'Additional slots that the game server will have. One way to use this variable is when selling an optional HLTV server since you want to increase the slot count by 1.'/*/T*/,
                                                                                'value'           => '',
                                                                        ),
                                                                'game_add_arg' => array (
                                                                                'type'            => 'text',
                                                                                'label'           => /*T*/'game_add_arg'/*/T*/,
                                                                                'description'     => /*T*/'Specifies any additional arguments that will be added to the default command line. Works like additional arguments in the game server\'s service settings.'/*/T*/,
                                                                                'value'           => '',
                                                                        ),
                                                                'game_branded' => array (
                                                                                'type'            => 'yesno',
                                                                                'label'           => /*T*/'game_branded'/*/T*/,
                                                                                'description'     => /*T*/'Specifies if the game server will be branded. <b>Values</b>: 1=Yes, 0=No'/*/T*/,
                                                                                'value'           => '0',
                                                                        ),
                                                                'game_private' => array (
                                                                                'type'            => 'yesno',
                                                                                'label'           => /*T*/'game_private'/*/T*/,
                                                                                'description'     => /*T*/'Specifies if the game server will be password protected. <b>Values</b>: 1=Yes, 0=No'/*/T*/,
                                                                                'value'           => '0',
                                                                        ),
                                                                'game_level' => array (
                                                                                'type'            => 'text',
                                                                                'label'           => /*T*/'game_level'/*/T*/,
                                                                                'description'     => /*T*/'The game server will only be created in servers with the specified service level.<br /> This can be used to sell game servers with different levels of bandwidth, hardware, support, etc.'/*/T*/,
                                                                                'value'           => '',
                                                                        ),
                                                                'voice_datacenter' => array (
                                                                                'type'            => 'text',
                                                                                'label'           => /*T*/'voice_datacenter'/*/T*/,
                                                                                'description'     => /*T*/'The datacenter where the voice server will be created. This is only used if skip_setup_page = 1'/*/T*/,
                                                                                'value'           => '',
                                                                        ),
                                                                'voice_hostname' => array (
                                                                                'type'            => 'text',
                                                                                'label'           => /*T*/'voice_hostname'/*/T*/,
                                                                                'description'     => /*T*/'The voice server\'s hostname. This is only used if skip_setup_page = 1'/*/T*/,
                                                                                'value'           => '',
                                                                        ),
                                                                'voice_rcon_password' => array (
                                                                                'type'            => 'text',
                                                                                'label'           => /*T*/'voice_rcon_password'/*/T*/,
                                                                                'description'     => /*T*/'The voice server\'s rcon password. This is only used if skip_setup_page = 1'/*/T*/,
                                                                                'value'           => '',
                                                                        ),
                                                                'voice_private_password' => array (
                                                                                'type'            => 'text',
                                                                                'label'           => /*T*/'voice_private_password'/*/T*/,
                                                                                'description'     => /*T*/'The voice server\'s private password.<br /> This is only used if skip_setup_page = 1, voice_private = 1 and voice_id isn\'t TEAMSPEAK'/*/T*/,
                                                                                'value'           => '',
                                                                        ),
                                                                'voice_id' => array (
                                                                                'type'            => 'text',
                                                                                'label'           => /*T*/'voice_id'/*/T*/,
                                                                                'description'     => /*T*/'The voice id of the new voice sever. Set to blank or NONE to disable.<br /> Values are TEAMSPEAK for Teamspeak 2 and TCXXXXXXXXX for any other game server. Replace XXXXXXXXX with the Id assigned in TCAdmin.'/*/T*/,
                                                                                'value'           => '',
                                                                        ),
                                                                'voice_slots' => array (
                                                                                'type'            => 'text',
                                                                                'label'           => /*T*/'voice_slots'/*/T*/,
                                                                                'description'     => /*T*/'The slots that the voice server will have.<br /> If set to blank and a valid game server and slot have been specified, the voice server will use the same number of slots.<br /> If set to 0 or blank and no game slots have been specified, no voice server will be created.'/*/T*/,
                                                                                'value'           => '',
                                                                        ),
                                                                'voice_add_slots' => array (
                                                                                'type'            => 'text',
                                                                                'size'            => '3',
                                                                                'label'           => /*T*/'voice_add_slots'/*/T*/,
                                                                                'description'     => /*T*/'Additional slots that the voice server will have. One way to use this variable is by selling extra slots.'/*/T*/,
                                                                                'value'           => '',
                                                                        ),
                                                                'voice_private' => array (
                                                                                'type'            => 'yesno',
                                                                                'label'           => /*T*/'voice_private'/*/T*/,
                                                                                'description'     => /*T*/'Specifies if the game server will be password protected. <b>Values</b>: 1=Yes, 0=No'/*/T*/,
                                                                                'value'           => '0',
                                                                        ),
                                                                'voice_level' => array (
                                                                                'type'            => 'text',
                                                                                'label'           => /*T*/'voice_level'/*/T*/,
                                                                                'description'     => /*T*/'Same as game_level except for voice servers.'/*/T*/,
                                                                                'value'           => '',
                                                                        ),
                                                                'switch_at_once' => array (
                                                                                'type'            => 'text',
                                                                                'label'           => /*T*/'switch_at_once'/*/T*/,
                                                                                'description'     => /*T*/'The number of game servers that can be running at once.<br /> Set to 0 or blank to disable game switching.'/*/T*/,
                                                                                'value'           => '',
                                                                )
                                            ),
        ),
        /*T*/"Actions"/*/T*/ => array (
                                    "type"          => "hidden",
                                    "description"   => /*T*/"current actions that are active for this plugin_per server"/*/T*/,
                                    "value"         => "Create,Delete,Suspend,UnSuspend"
        )
        );
        return $variables;
    }

    function create($args) {
        $args = $this->set_all_args($args);
        $error="";
        $addons = @$args['package']['addons'];
        $master = $args['package']['variables'];
        $use = array();

        // addons should overwrite package vars
        if (isset($addons) && is_array($addons)) {
            foreach ($addons as $key => $result) {
                if ($result != '')
                    $master[$key] = $result;
            }
        }
        foreach ($master as $key => $result) {
            if (isset($master[$key]) && $result != '') {
                $use[$key] = $result;
            }
        }
        //If its a package addon, it will still work. Of course, it can be in the variables down below, or in a package addon, This is for flexibility. It will use the vars first, if null, then use the package addon, if null, then use the Location.
        if (@$use['game_datacenter'] == '') {
            if ($args['server']['variables']['plugin_tcadmin_location_custom_field'] != '') {
                $use['game_datacenter'] = $args['server']['variables']['plugin_tcadmin_location_custom_field'];
            }
        }

        //If its a package addon, it will still work. Of course, it can be in the variables down below, or in a package addon, This is for flexibility. It will use the vars first, if null, then use the package addon, if null, then use the Location.
        if (@$use['voice_datacenter'] == '') {
            if (@$args['server']['variables']['plugin_tcadmin_voice_location_custom_field'] != '') {
                $use['voice_datacenter'] = $args['server']['variables']['plugin_tcadmin_voice_location_custom_field'];
            }
        }

        // assign the custom fields to the proper values
        if (isset($args['game_hostname']) && $args['game_hostname'] != '')
            $use['game_hostname'] = $args['game_hostname'];
        if (isset($args['game_rcon_password']) && $args['game_rcon_password'] != '')
            $use['game_rcon_password'] = $args['game_rcon_password'];
        if (isset($args['game_private_password']) && $args['game_private_password'] != '')
            $use['game_private_password'] = $args['game_private_password'];
        if (isset($args['voice_hostname']) && $args['voice_hostname'] != '')
            $use['voice_hostname'] = $args['voice_hostname'];
        if (isset($args['voice_rcon_password']) && $args['voice_rcon_password'] != '')
            $use['voice_rcon_password'] = $args['voice_rcon_password'];
        if (isset($args['voice_private_password']) && $args['voice_private_password'] != '')
            $use['voice_private_password'] = $args['voice_private_password'];

        $username = $args['server']['variables']['plugin_tcadmin_client_username_field'];
        $nUsername = '';
        //The $username will stay the same of how it is exactly, its corrected here
        $uSearch =     'abcdefghijklmnopqrstuvwxyz1234567890-_'; //This is the chars that are allowed by tcadmin. If these are not in there username, there removed.
        for ($i = 0;$i < strlen($username);$i++) {
            if (strstr($uSearch,strtolower($username{$i}))) {
                $nUsername .= $username{$i};
            }
        }
        //If the username is Nothing lets use there first name.
        if ($nUsername == '') { $nUsername = $args['customer']['first_name']; }
        //Limit the username to 15 chars.
        if (strlen($nUsername) > 15) {
            $nUsername = mb_substr($nUsername,0,15);
        }
        //Limit the password to 25 chars.
        if (strlen($args['server']['variables']['plugin_tcadmin_client_password_field']) > 25) {
            $args['server']['variables']['plugin_tcadmin_client_password_field'] = mb_substr($args['server']['variables']['plugin_tcadmin_client_password_field'],0,25);
        }
        //Remove & signs. this is done here so it can update later.
        if (strstr($args['server']['variables']['plugin_tcadmin_client_password_field'],'&')) {
            $args['server']['variables']['plugin_tcadmin_client_password_field'] = str_replace('&','',$args['server']['variables']['plugin_tcadmin_client_password_field']);
        }
        $urlvars = array(
        'function' => 'AddPendingSetup',
        'game_package_id' => @$args['package']['id'],
        'voice_package_id' => @$args['package']['id'],
        'client_id' => @$args['customer']['id'],
        'user_email' => @$args['customer']['email'],
        'user_fname' => @$args['customer']['first_name'],
        'user_lname' => @$args['customer']['last_name'],
        'user_address1' => @$args['user_information']['address'],
        'user_address2' => '',
        'user_city' => @$args['user_information']['city'],
        'user_state' => @$args['user_information']['state'],
        'user_zip' => @$args['user_information']['zip'],
        'user_country' => @$args['user_information']['country'],
        'user_phone1' => @$args['user_information']['phone'],
        'user_phone2' => '',
        'user_name' => $nUsername,
        'user_password' => @$args['server']['variables']['plugin_tcadmin_client_password_field'],
        'game_id' => @$use['game_id'],
        'game_slots' => @$use['game_slots'],
        'game_private' => @$use['game_private'],
        'game_additional_slots' => @$use['game_add_slots'],
        'game_branded' => @$use['game_branded'],
        'game_additional_arguments' => @$use['game_add_arg'],
        'switch_at_once' => @$use['switch_at_once'],
        'voice_id' => @$use['voice_id'],
        'voice_slots' => @$use['voice_slots'],
        'voice_private' => @$use['voice_private'],
        'voice_additional_slots' => @$use['voice_add_slots'],
        'skip_page' => @$use['skip_setup_page'],
        'game_datacenter' => @$use['game_datacenter'],
        'game_hostname' => @$use['game_hostname'],
        'game_rcon_password' => @$use['game_rcon_password'],
        'game_private_password' => @$use['game_private_password'],
        'voice_datacenter' => @$use['voice_datacenter'],
        'voice_hostname' => @$use['voice_hostname'],
        'voice_rcon_password' => @$use['voice_rcon_password'],
        'voice_private_password' => @$use['voice_private_password']
        );
        $error = false;
        $createResponse = '';
        $tcAdminResponse = array();
        $create = $this->sendtotcadmin($args,$urlvars);
        if (is_a($create, 'CE_Error')) {
            $error = true;
        } else {
            //The format of this is: return_code        return_text        error_code        error_text
            $createResponse = explode("\t",$create);
            $tcAdminResponse = array(
                                    'return_code' => $createResponse[0],
                                    'return_text' => $createResponse[1],
                                    'error_code' => $createResponse[2],
                                    'error_text' => $createResponse[3]
            );
            if (count($createResponse) == 0) {
                //No response received.
                $error = true;
            }
            elseif ($tcAdminResponse['error_code'] != '0' || $tcAdminResponse['return_code'] != '0') {
                //Error, something wasnt correct.
                $error = true;
            }
            else {
                if ($tcAdminResponse['return_text'] != $username) {
                    if ($tcAdminResponse['return_text'] != '') {
                        $args['package_class']->setCustomField($args['server']['variables']['plugin_tcadmin_Client_Username_Custom_Field'], $tcAdminResponse['return_text'], CUSTOM_FIELDS_FOR_PACKAGE);
                    }
                }
            }
        }
        if ($error == true) {
            $parms = print_r($urlvars,1);
            $parms2 = print_r($tcAdminResponse,1);
            $message = "Alert, the tcadmin plugin hasn't executed fully, and resulted in a FAILED action. Please review this email as it contains information about what has happen
Client Email: ".$args['customer']['email']."
Client name: ".$args['customer']['first_name']." ".$args['customer']['last_name']."
Package ID: ".$args['package']['id']."
Package Name: ".$args['package']['name']."
Settings sent to Tcadmin, results are as follow, [NAME] => VALUE, the name is the name of the action, the value is what was set.

$parms


Tcadmin has responded with the following:

$parms2

----- Things to check for the server properties -----
Ensure the server settings are set correct, In Clientexec goto Admin->Servers select the Tcadmin server, check the following:
Billing API url, This should be the url to your billingapi.aspx. Such as http://panel.yoursite.com/billingapi.aspx

Admin username, This should be the administrators username, the one you login with on tcadmin.

Admin password, This is the password you use to login with the above admin username, on tcadmin.

Client Username Custom Field, Should be the exact name that you had used for the clients username field, See: Admin->Customfields, Select the package custom fields. The field name should match.

Client Password Custom Field, Same as for the username custom field.

Location Custom Field, The name should also match, Using this you should have the Location set to a drop down menu, For the options you should have the Name of the location, and the tcadmin datacenters id in (), so it looks like \"West (TC12382189398120)\"

If you wish to use ( or ) in the options, escape it with a \\ Example: La \\(Level3!\\)(TC1283912893219). To specify more than one location, sperate each one by a comma. An example would be: North (TC239210931209),East (TC92193912090), North (TC892138129321)

The Datacenters id can be located by logging into your game panel, Going to System Settings->Datacenters, There you will see the datacenters ID.

---- Check the package settings ----
Make sure the game_slots value isn't blank (You can see it above.)

Make sure the game_id value is set (Look above.)

Make sure the game_datacenter is set.

If game_level is being used, ensure you have a datacenter with the same game level.


In most cases, there will be an error message above. (Which is the [error_text])

Here is a few simple error messages and how to fix them:

Access denied: Check the username/password you had entered in for the Admin username/password.

Blank response: This is normally caused by the IP of the webserver not being allowed to use the billing api. To add this, in Tcadmin goto System Settings -> Plugins -> under TC_GSAUTO Click configure. Scroll down until you see Billing API Settings
Make sure the Billing api is checked.
Under Allowed IPs, enter your webservers ip Note: Enter one ip per line.

";
            $this->error_mail($args,$message,'Tcadmin server creation failed.');
            throw new CE_Exception('An error has occured, a detailed explantion has been emailed to '.$args['server']['variables']['plugin_tcadmin_Error_Email']);
        }
    }

    function update($args) {
        //Right now tcadmins billingapi doesn't support this. We might create a secondary script for tcadmin to interact with updates/upgrades.
    }

    function delete($args){
        $urlvars = array(
                        'function' => 'DeleteGameAndVoiceByBillingID',
                        'client_package_id' => $args['package']['id']
        );
        $delete = $this->sendtotcadmin($args,$urlvars);
    }

    function suspend($args){
                $urlvars = array(
                        'function' => 'SuspendGameAndVoiceByBillingID',
                        'client_package_id' => $args['package']['id']
                );
            $suspend = $this->sendtotcadmin($args,$urlvars);
    }
    function unsuspend($args){
                $urlvars = array(
                        'function' => 'UnSuspendGameAndVoiceByBillingID',
                        'client_package_id' => $args['package']['id']
                );
                $unsuspend = $this->sendtotcadmin($args,$urlvars);
    }
    //This function is just a simple way of setting all of the arguments, maybe used later in other things such as an upgrade option.
    function set_all_args($args) {
        $package = new UserPackage($args['package']['id'], $this->user);
        $clientuser = "";
        $clientpass = "";
        $location = "";
        $gameHostname = "";
        $gameRconPassword = "";
        $gamePrivatePassword = "";
        $voiceHostname = "";
        $voiceRconPassword = "";
        $voicePrivatePassword = "";
        $clientuser = $package->getCustomField($args['server']['variables']['plugin_tcadmin_Client_Username_Custom_Field'], CUSTOM_FIELDS_FOR_PACKAGE);
        $clientpass = $package->getCustomField($args['server']['variables']['plugin_tcadmin_Client_Password_Custom_Field'], CUSTOM_FIELDS_FOR_PACKAGE);
        $location = $package->getCustomField($args['server']['variables']['plugin_tcadmin_Location_Custom_Field'], CUSTOM_FIELDS_FOR_PACKAGE);
        $gameHostname = $package->getCustomField($args['server']['variables']['plugin_tcadmin_Game_Hostname_Custom_Field'], CUSTOM_FIELDS_FOR_PACKAGE);
        $gameRconPassword = $package->getCustomField($args['server']['variables']['plugin_tcadmin_Game_Rcon_Password_Custom_Field'], CUSTOM_FIELDS_FOR_PACKAGE);
        $gamePrivatePassword = $package->getCustomField($args['server']['variables']['plugin_tcadmin_Game_Private_Password_Custom_Field'], CUSTOM_FIELDS_FOR_PACKAGE);
        $voiceHostname = $package->getCustomField($args['server']['variables']['plugin_tcadmin_Voice_Hostname_Custom_Field'], CUSTOM_FIELDS_FOR_PACKAGE);
        $voiceRconPassword = $package->getCustomField($args['server']['variables']['plugin_tcadmin_Voice_Rcon_Password_Custom_Field'], CUSTOM_FIELDS_FOR_PACKAGE);
        $voicePrivatePassword = $package->getCustomField($args['server']['variables']['plugin_tcadmin_Voice_Private_Password_Custom_Field'], CUSTOM_FIELDS_FOR_PACKAGE);
        $args['server']['variables']['plugin_tcadmin_client_username_field'] = $clientuser;
        $args['server']['variables']['plugin_tcadmin_client_password_field'] = $clientpass;
        $args['server']['variables']['plugin_tcadmin_location_custom_field'] = $location;
        $args['game_hostname'] = $gameHostname;
        $args['game_rcon_password'] = $gameRconPassword;
        $args['game_private_password'] = $gamePrivatePassword;
        $args['voice_hostname'] = $voiceHostname;
        $args['voice_rcon_password'] = $voiceRconPassword;
        $args['voice_private_password'] = $voicePrivatePassword;
        $clientUser = new User($args['customer']['id']);
        $address = $clientUser->getAddress();
        $phone = $clientUser->getPhone();
        $zip = $clientUser->getZipCode();
        $city = $clientUser->getCity();
        $state = $clientUser->getState();
        $country = $clientUser->getCountry();
        $args['user_information'] = array(
                'address' => mb_substr($address,0,150),
                'phone' => mb_substr($phone,0,20),
                'zip' => mb_substr($zip,0,10),
                'city' => mb_substr($city,0,50),
                'state' => mb_substr($state,0,50),
                'country' => mb_substr($country,0,2)
        );
        $args['package_class'] = $package;
        return $args;
    }

    function error_mail($args,$message,$subject)
    {
        $to = $args['server']['variables']['plugin_tcadmin_Error_Email'];
        $mailGateway = new NE_MailGateway();
        $mailGateway->mailMessageEmail($message,$this->settings->get('Support E-mail'),'[CE] Tcadmin plugin',$to,'',$subject,"","");
    }

    function sendtotcadmin($args,$values) {
        $post = 'tcadmin_username='.urlencode($args['server']['variables']['plugin_tcadmin_Admin_username']).'&tcadmin_password='.urlencode($args['server']['variables']['plugin_tcadmin_Admin_password']).'&response_type=text';
        foreach ($values as $key => $value) {
            $post .= "&$key=".urlencode(str_replace('\'','',$value));
        }
        $url = $args['server']['variables']['plugin_tcadmin_Billing_API_url'];
        $result = NE_Network::curlRequest($this->settings, $url, $post, false, true);
        return $result;
    }

    function doCreate($args)
    {
        $userPackage = new UserPackage($args['userPackageId']);
        $this->create($this->buildParams($userPackage));
        return 'Package has been created.';
    }

    function doSuspend($args)
    {
        $userPackage = new UserPackage($args['userPackageId']);
        $this->suspend($this->buildParams($userPackage));
        return 'Package has been suspended.';
    }

    function doUnSuspend($args)
    {
        $userPackage = new UserPackage($args['userPackageId']);
        $this->unsuspend($this->buildParams($userPackage));
        return 'Package has been unsuspended.';
    }

    function doDelete($args)
    {
        $userPackage = new UserPackage($args['userPackageId']);
        $this->delete($this->buildParams($userPackage));
        return 'Package has been deleted.';
    }
}
?>