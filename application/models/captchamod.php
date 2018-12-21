<?php
	//==========================================================//
class Captchamod extends CI_Model {
      
        function __construct()
    {
        // Call the Model constructor
        $this->load->helper('file');
        parent::__construct();
    } 
      
   ///////////////////////////////////
function imagerotate( $source_image, $angle, $bgd_color ) {

            $angle = 360-$angle; // GD rotates CCW, imagick rotates CW

            foreach ( array( '/usr/bin', '/usr/local/bin', '/opt/local/bin', '/sw/bin' ) as $path ) {

                if ( @file_exists( $path . '/convert' ) ) {
                    $imagick = $path . '/convert';
                    if ( $path == '/opt/local/bin' ) {
                        $imagick = 'DYLD_LIBRARY_PATH="" ' . $imagick; // some kind of conflict with MacPorts and MAMP
                    }
                    break;
                }

            }

            if ( !isset( $imagick ) ) {

                //trigger_error( 'imagerotate(): could not find imagemagick binary, original image returned', E_USER_WARNING );
                return $source_image;

            }

            $file1 = '/tmp/imagick_' . rand( 10000,99999 ) . '.png';
            $file2 = '/tmp/imagick_' . rand( 10000,99999 ) . '.png';

            if ( @imagepng( $source_image, $file1 ) ) {

                exec( $imagick . ' -rotate ' . $angle . ' ' . $file1 . ' ' . $file2 );

                if ( file_exists( $file2 ) ) {

                    $new_image = imagecreatefrompng( $file2 );
                    unlink( $file1 );
                    unlink( $file2 );
                    return $new_image;

                } else {

                    //trigger_error( 'imagerotate(): imagemagick conversion failed, original image returned', E_USER_WARNING );
                    return $source_image;

                }

            } else {

                //trigger_error( 'imagerotate(): could not write to ' . $file1 . ', original image returned', E_USER_WARNING );
                return $source_image;

            }

        }
/////////////////////////////////////



    
    function captcha($r,$g,$b,$tr,$tg,$tb)
    {
            $filepng = ''; 
            for ($i = 0; $i<5; $i++)
            {
                $filepng .= rand(0, 255);
            } 
            $filepng .= '.png';
            
            
            
            //$im5 = imagecreatefrompng('/captcha/'.$filepng);
            
            $font = imageloadfont('./gdfonts/bubblebath.gdf');
            $fontheight = imagefontheight($font);
            $fontwidth = imagefontwidth($font);
            
            
            //$im = imagecreate(40, 40);
            $im1 = imagecreate(40, 40);       
            $im2 = imagecreate(40, 40);
            $im3 = imagecreate(40, 40);
            $im4 = imagecreate(40, 40);  
            $im5 = imagecreate(160, 40);  
            
            write_file('/captcha/'.$filepng, $im5);
            
               
            // White background and blue text
            //$bg = imagecolorallocate($im, 0, 0, 255);    
            //$textcolor = imagecolorallocate($im, 255, 255, 255);  
                          
            // White background and blue text
            $bg1 = imagecolorallocate($im1, $r, $g, $b);    
            $textcolor1 = imagecolorallocate($im1, $tr, $tg, $tb);  
                                                       
            // Color background end text
            $bg2 = imagecolorallocate($im2, $r, $g, $b);    
            $textcolor2 = imagecolorallocate($im2, $tr, $tg, $tb);  
            
            // Color background end text
            $bg3 = imagecolorallocate($im3, $r, $g, $b);    
            $textcolor3 = imagecolorallocate($im3, $tr, $tg, $tb);
            
            // Color background end text
            $bg4 = imagecolorallocate($im4, $r, $g, $b);    
            $textcolor4 = imagecolorallocate($im4, $tr, $tg, $tb);    
            
            
            $pool = '0123456789';  
            
            $name = '';
            
            for ($i = 1; $i<5; $i++)
            {
                $str = substr($pool, rand(0, strlen($pool) -1), 1);            
                $name .= $str;
                $word[$i] = $str;
                
                $deg[$i] = rand(0, 15);
                
                $x[$i] = rand(1, 10);
                $y[$i] = rand(1, 10);    
            }                  
            
            // input text in the image
            //imagestring($im, 5, $x[0], $y[0], $word[0], $textcolor);
            imagestring($im1, $font, $x[1], $y[1], $word[1], $textcolor1);
            imagestring($im2, $font, $x[2], $y[2], $word[2], $textcolor2);
            imagestring($im3, $font, $x[3], $y[3], $word[3], $textcolor3);
            imagestring($im4, $font, $x[4], $y[4], $word[4], $textcolor4);
            
            
            //$rotate = imagerotate($im, $deg[0], 0);
            $rotate1 = $this->imagerotate($im1, $deg[1], 0);
            $rotate2 = $this->imagerotate($im2, $deg[2], 0);
            $rotate3 = $this->imagerotate($im3, $deg[3], 0);
            $rotate4 = $this->imagerotate($im4, $deg[4], 0);
                                                       
            //imagecopy($im5, $rotate, 0, 0, 0, 0, 40, 40);
            imagecopy($im5, $rotate1, 0, 0, 0, 0, 40, 40);
            imagecopy($im5, $rotate2, 40, 0, 0, 0, 40, 40);
            imagecopy($im5, $rotate3, 80, 0, 0, 0, 40, 40);
            imagecopy($im5, $rotate4, 120, 0, 0, 0, 40, 40);    
            
            //header('Content-type: image/png');   
            
            // input 5 lines in the image
            for($i = 0; $i<3; $i++)
            {
                $x1 = rand(0, 3);
                $y1 = rand(0, 40);
                $x2 = rand(157, 160);
                $y2 = rand(0, 40);
                $r1 = rand(0, 255);
                $g1 = rand(0, 255);
                $b1 = rand(0, 255);
                $color = imagecolorallocate($im5, $r1, $g1, $b1);
                imageline($im5, $x1, $y1, $x2, $y2, $color);                
            } 

            imagepng($im5,'./captcha/'.$filepng);
            
            $filename = '/captcha/'.$filepng;
            //echo $filename;
            
            
            @imagedestroy($im5);
           @imagedestroy($im4);
            @imagedestroy($im3);
            @imagedestroy($im2);              
            @imagedestroy($im1);              
            //imagedestroy($im);              
            //imagedestroy($rotate);              
            @imagedestroy($rotate1);              
            @imagedestroy($rotate2);              
            @imagedestroy($rotate3);              
            @imagedestroy($rotate4);         
            
            return array('word' => $name,'filename' => $filename);     
	
    }
}
?>
