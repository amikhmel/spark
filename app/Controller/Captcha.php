<?php
/**
 * @author advisor
 *
 */
class Controller_Captcha extends Controller_Abstract {
    
    function defaultAction() {
        $_tmp = '';
        for ($i = 0; $i < 6; $i++) {
            $_tmp .= chr(rand(49, 57));
        }
        $_SESSION['SECURE_CODE'] = $_tmp;
        $this->DrawNoisedNumbers($_SESSION['SECURE_CODE']);
        die();
    }
    
    function GetRandomizedColor(&$im, $colors) {
        return imagecolorallocate($im, $colors["r"] + (rand(- 1 * $colors["d"], $colors["d"])), $colors["g"] + (rand(- 1 * $colors["d"], $colors["d"])), $colors["b"] + (rand(- 1 * $colors["d"], $colors["d"])));
    
    }
    
    function DrawNoisedNumbers($number) {
        
        header("Content-type: image/png");
        
        $i_width = 120;
        $i_height = 40;
        
        $i_bg_color = array("r" => 255, "g" => 255, "b" => 255, "d" => 5);
        $i_border_color = array("r" => 101, "g" => 144, "b" => 195, "d" => 0);
        
        $i_ln_color = array("r" => 155, "g" => 155, "b" => 155, "d" => 100);
        $i_text_color = array("r" => 80, "g" => 80, "b" => 80, "d" => 70);
        $i_noise = array("r" => 192, "g" => 192, "b" => 192, "d" => 50, "back" => 100, "front" => 300);
        
        $v_lines_count = 7;
        $v_lines_dist = ceil($i_height / $v_lines_count);
        
        $h_lines_count = 7;
        $h_lines_dist = ceil($i_width / $h_lines_count);
        
        $im = @imagecreatetruecolor($i_width, $i_height) or die("Cannot Initialize new GD image stream");
        
        $background_color = imagecolorallocate($im, 241, 245, 250);
        imagefilledrectangle($im, 0, 0, $i_width, $i_height, $background_color);
        // Drawing horizontal lines
        for ($i = 0; $i < $v_lines_count; $i++) {
            $lines_color = $this->GetRandomizedColor($im, $i_ln_color);
            $rln_1 = rand(0, 8);
            $rln_2 = rand(0, 8);
            imageline($im, 0, $v_lines_dist * $i + ($rln_1 - 2), $i_width, $v_lines_dist * $i + ($rln_2 - 2), $lines_color);
        }
        
        // Drawing vertical lines
        for ($i = 0; $i < $h_lines_count; $i++) {
            $lines_color = $this->GetRandomizedColor($im, $i_ln_color);
            $rln_1 = rand(0, 10);
            $rln_2 = rand(0, 6);
            imageline($im, $h_lines_dist * $i + ($rln_1 - 3), 0, $h_lines_dist * $i + ($rln_2 - 3), $i_height, $lines_color);
        }
        // Drawing random noise over background
        for ($i = 0; $i < $i_noise["back"]; $i++) {
            if ($i % 50 == 0) {
                $_color = $this->GetRandomizedColor($im, $i_noise);
            }
            $_x = rand(0, $i_width);
            $_y = rand(0, $i_height);
            imageline($im, $_x, $_y, $_x + 1, $_y + 1, $_color);
            
        }
        // drawing number
        $rnd = $number;
        $font_file = Bootstrap::getFilePath('Fonts/lucon.ttf');
        for ($i = 0; $i < strlen($rnd); $i++) {
            $x = rand(0, 5) + $i * 18 + 5;
            $y = rand(0, 10) + 22;
            $text_color = $this->GetRandomizedColor($im, $i_text_color);
            srand();
            imagettftext($im, 18 + rand(-2, 2), rand(-40, 40), $x, $y, $text_color, $font_file, substr($rnd, $i, 1));
        
        }
        
        // Drawing random noise over foreground
        for ($i = 0; $i < $i_noise["front"]; $i++) {
            $_x = rand(0, $i_width);
            $_y = rand(0, $i_height);
            $_color = $this->GetRandomizedColor($im, $i_noise);
            
            imageline($im, $_x, $_y, $_x - 1, $_y - 1, $_color);
        }
        //drawind border
        $bordercolor = $this->GetRandomizedColor($im, $i_border_color);
        imageline($im, 0, 0, 0, $i_height - 1, $bordercolor);
        imageline($im, 0, $i_height - 1, $i_width - 1, $i_height - 1, $bordercolor);
        imageline($im, $i_width - 1, $i_height - 1, $i_width - 1, 0, $bordercolor);
        imageline($im, $i_width - 1, 0, 0, 0, $bordercolor);
        
        imagepng($im);
        imagedestroy($im);
    }

}