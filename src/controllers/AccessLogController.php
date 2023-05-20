<?php
require_once('../../src/models/Musers.php');


class AccessLogController
{
    /**
     * get user's IP address
     */
    public static function getUserIpAddress() {
        $ip = null; 
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if($ip === null) {
            throw new Exception('Unable to determine IP address.');
            // die("Unable to determine IP address.");
        }

        return $ip;
    }


    /**
     * get user's OS, among the most common OSs
     */
    public static function getUserOS() {
    if (!isset($_SERVER['HTTP_USER_AGENT'])) {
        throw new Exception('User agent is not defined.');
    }

    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $os_platform  = "Unknown OS Platform";

    $os_array = array(
        '/windows nt 10/i'      => 'Windows 10',
        '/windows nt 6.3/i'     => 'Windows 8.1',
        '/windows nt 6.2/i'     => 'Windows 8',
        '/windows nt 6.1/i'     => 'Windows 7',
        '/windows nt 6.0/i'     => 'Windows Vista',
        '/windows nt 5.2/i'     => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i'     => 'Windows XP',
        '/windows xp/i'         => 'Windows XP',
        '/windows nt 5.0/i'     => 'Windows 2000',
        '/windows me/i'         => 'Windows ME',
        '/win98/i'              => 'Windows 98',
        '/win95/i'              => 'Windows 95',
        '/win16/i'              => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i'        => 'Mac OS 9',
        '/linux/i'              => 'Linux',
        '/ubuntu/i'             => 'Ubuntu',
        '/iphone/i'             => 'iPhone',
        '/ipod/i'               => 'iPod',
        '/ipad/i'               => 'iPad',
        '/android/i'            => 'Android',
        '/blackberry/i'         => 'BlackBerry',
        '/webos/i'              => 'Mobile',
    );

    foreach ($os_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $os_platform = $value;
        }
    }

    return $os_platform;
    }


    /**
     * Update access log 
     * using global $_SERVER array 
     */
    public static function updateAccessLog($db, $email){
        if($db == null){
            error_log("Database not initialized");
            throw new Exception('Database not initialized');
            die("Database not initialized");
        }
    
        try{
            $stmt = $db->prepare('INSERT INTO AccessLog (user_id, ip_address, operating_system) VALUES (:user_id, :ip_address, :operating_system)');
            $user = User::getUserByEmail($db, $email);
            if ($user === null) {
                throw new Exception("User with the email $email not found.");
                die("User with the email $email not found.");
            }

            $userId = $user->getUserID();
            $ipAddress = AccessLogController::getUserIpAddress();
            $operatingSystem = AccessLogController::getUserOS();

            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':ip_address', $ipAddress, PDO::PARAM_STR);
            $stmt->bindParam(':operating_system', $operatingSystem, PDO::PARAM_STR);
            $stmt->execute();

        } catch (PDOException $e) {
            // PDO exceptions
            echo "Database error: " . $e->getMessage();
            die("Problem with database");
        } catch (Exception $e) {
            // TGeneral exceptions
            echo "An error occurred: " . $e->getMessage();
            die("An error occurred: " . $e->getMessage());
        }
    }

    /**
     * get access log of a user
     */
    public static function getLog($db, $userId){
        $stmt = $db->prepare('SELECT * FROM AccessLog WHERE user_id = ? ORDER BY access_time DESC');
        $stmt->bindValue(1, $userId, SQLITE3_INTEGER);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }    
}
