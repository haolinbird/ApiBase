<?php
/**
 * File: CDN.php
 *
 * @author meijuanl <meijuanl@jumei.com>
 * @date 2019-2-12
 */

namespace Util\Utility;

/**
 * Class CDN .
 */
class CDN {

    static public function make_cdn_filename_by_time($user_filename) {
        list($usec, $sec) = explode(" ", microtime());
        $new_filename = time() . intval($usec * 10000) . "." . pathinfo($user_filename, PATHINFO_EXTENSION);
        return $new_filename;
    }


    static public function move_file_to_cdn($sLocalFile, $sCDNFilePath, $sCDNFileName,  $cdn_server_info, & $sCDNFileFullUrl) {
        $ret = false;

        try
        {

            $local_filefullname = $sLocalFile; //resolveUrl($sFileDir) . $sFileName;
            $fp = @fopen($local_filefullname , 'r');
            if(!$fp) {
                return false;
            }
            if(!is_array($sCDNFilePath))
                $sCDNFilePath = explode ("/", $sCDNFilePath);

            $conn_id = @ftp_connect($cdn_server_info['ftp_host'], $cdn_server_info['ftp_port'], 15);

            if($conn_id)
            {
                $ret = @ftp_login($conn_id, $cdn_server_info['ftp_username'], $cdn_server_info['ftp_password']);
                if($ret)
                {
                    @ftp_pasv($conn_id, true);

                    foreach($sCDNFilePath as $dirname)
                    {
                        if($ret)
                        {
                            $chdir_ok = false;
                            try
                            {
                                //there is a WARNING to EXCEPTION handler in system ....
                                $chdir_ok = @ftp_chdir($conn_id, $dirname);
                            }catch(Exception $ex){}

                                if( ! $chdir_ok )
                                {
                                    $ret = $ret && (ftp_mkdir($conn_id, "$dirname") !== false);
                                    $ret = $ret && ftp_chdir($conn_id, "$dirname");
                                }
                        }
                    }

                    $ret = $ret && @ftp_fput($conn_id, $sCDNFileName, $fp, FTP_BINARY);

                    if ($ret)
                    {
                        $sCDNFileFullUrl = $cdn_server_info['http_base_url'] . join("/", $sCDNFilePath) . "/" . $sCDNFileName;
                    }
                }
                @ftp_close($conn_id);
            }
            @fclose($fp);
            @unlink($local_filefullname);
        }catch(Exception $ex){}
            return $ret;
    }
}

