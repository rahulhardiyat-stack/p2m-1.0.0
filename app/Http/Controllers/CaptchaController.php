<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CaptchaController extends Controller
{
    /**
     * Generate a new captcha image.
     */
    public function generate(Request $request)
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $captchaText = '';
        for ($i = 0; $i < 6; $i++) {
            $captchaText .= $characters[rand(0, strlen($characters) - 1)];
        }

        session(['captcha_answer' => strtolower($captchaText)]);

        $manager = new ImageManager(new Driver());
        
        $width = 280;
        $height = 80;
        
        $image = $manager->create($width, $height)->fill('#ffffff');

        // Explicitly load a standard font to ensure correct size rendering
        $fontPath = base_path('vendor/dompdf/dompdf/lib/fonts/DejaVuSans-Bold.ttf');
        if (!file_exists($fontPath)) {
             $fontPath = base_path('vendor/dompdf/dompdf/lib/fonts/DejaVuSans.ttf');
        }

        // Add some noise (lines/shapes)
        for ($i = 0; $i < 4; $i++) {
            $colors = ['#cbd5e1', '#e2e8f0'];
            $color = $colors[rand(0, count($colors) - 1)];
            
            $image->drawLine(function ($line) use ($width, $height, $color) {
                $line->from(0, rand(0, $height));
                $line->to($width, rand(0, $height));
                $line->color($color);
                $line->width(1);
            });
        }

        // Add the text with a clearly readable but balanced font size
        $x = 40;
        for ($i = 0; $i < strlen($captchaText); $i++) {
            $colors = ['#1e1b4b', '#312e81', '#4338ca', '#3730a3', '#1d4ed8'];
            $color = $colors[rand(0, count($colors) - 1)];
            
            $char = $captchaText[$i];
            $image->text($char, $x, 50, function ($font) use ($color, $fontPath) {
                if (file_exists($fontPath)) {
                    $font->filename($fontPath);
                }
                $font->size(32); // Reduced from 48 for better balance
                $font->color($color);
                $font->align('center');
                $font->valign('middle');
                $font->angle(rand(-8, 8)); 
            });
            $x += 40;
        }

        return response($image->toPng(), 200, ['Content-Type' => 'image/png']);
    }
}
