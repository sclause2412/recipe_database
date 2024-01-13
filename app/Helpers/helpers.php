<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

if (!function_exists('backorgo')) {
    function backorgo($url): RedirectResponse
    {
        // request()->fullUrl() reorders query parameters, therefore it is not reliable to get the correct comparison
        // Url must be compared manually


        if (request()->method() == 'GET') {
            $back = parse_url(back()->getTargetUrl());
            $here = parse_url(request()->fullUrl());
            parse_str($back['query'] ?? null, $back['query']);
            parse_str($here['query'] ?? null, $here['query']);

            if ($back == $here)
                return new RedirectResponse($url);
        }

        return back();
    }
}

if (!function_exists('backorhome')) {
    function backorhome(): RedirectResponse
    {
        return backorgo('/');
    }
}

if (!function_exists('user_icon_color')) {
    function user_icon_color(\App\Models\User $user)
    {
        if (is_null($user))
            return 'text-gray-800 dark:text-gray-200';

        if ($user->admin)
            $class = 'text-red-600 dark:text-red-500';
        else
            $class = 'text-green-600 dark:text-green-500';

        if ($user->id == Auth::user()->id)
            $class .= ' bg-yellow-300 dark:bg-yellow-900';

        if (!$user->active || is_null($user->email_verified_at))
            $class .= ' opacity-25';

        return $class;
    }
}

if (!function_exists('from_array')) {
    function from_array($needle, $haystack, $default = null)
    {
        if (is_array($haystack)) {
            if (isset($haystack[$needle]))
                return $haystack[$needle];
            if (in_array($needle, $haystack))
                return $needle;
        } else {
            if ($needle == $haystack)
                return $needle;
        }

        return $default;
    }
}

if (!function_exists('is_user')) {
    function is_user(User $user = null)
    {
        if (is_null($user))
            $user = Auth::user();

        if (is_null($user))
            return false;

        return true;
    }
}

if (!function_exists('is_admin')) {
    function is_admin(User $user = null)
    {
        if (is_null($user))
            $user = Auth::user();

        if (is_null($user))
            return false;

        if (!$user->admin)
            return false;

        return true;
    }
}

if (!function_exists('is_last_admin')) {
    function is_last_admin(User $user = null)
    {
        if (is_null($user))
            $user = Auth::user();

        if (is_null($user))
            return false;

        if (!$user->admin)
            return false;

        if (User::where('admin', true)->whereNot('id', $user->id)->count() == 0)
            return true;

        return false;
    }
}

if (!function_exists('is_elevated')) {
    function is_elevated(User $user = null)
    {
        if (is_null($user))
            $user = Auth::user();

        if (is_null($user))
            return false;

        if (!$user->admin)
            return false;

        if ($user->elevated)
            return true;

        return false;
    }
}

if (!function_exists('random_float')) {

    function random_float($min, $max)
    {
        return random_int($min * 10000, $max * 10000) / 10000;
    }
}

if (!function_exists('HSVtoRGB')) {

    /**
     * Convert HSV color to RGB
     *
     * This function converts colors in HSV format to RGB format. The calculation
     * is described at https://en.wikipedia.org/wiki/HSL_and_HSV
     *
     * @param int $iH The Hue of the color in the range from 0 to 360.
     *                Instead of a single value an array holding all parameters
     *                at once can be used.
     * @param int $iS The Saturation of the color in the range from 0 to 100
     * @param int $iV The Value of the color in the range from 0 to 100
     *
     * @return array Returns an array of the red, green and blue component of the
     *               color. The range is from 0 to 255.
     *
     */

    function HSVtoRGB($iH, $iS = 0, $iV = 0)
    {
        if (is_array($iH)) {
            $iS = $iH[1] ?? 0;
            $iV = $iH[2] ?? 0;
            $iH = $iH[0];
        }

        if ($iH < 0)
            $iH = 0; // Hue:
        if ($iH > 360)
            $iH = 360; //   0-360
        if ($iS < 0)
            $iS = 0; // Saturation:
        if ($iS > 100)
            $iS = 100; //   0-100
        if ($iV < 0)
            $iV = 0; // Lightness:
        if ($iV > 100)
            $iV = 100; //   0-100

        $dS = $iS / 100.0; // Saturation: 0.0-1.0
        $dV = $iV / 100.0; // Lightness:  0.0-1.0

        $dC = $dV * $dS; // Chroma:     0.0-1.0
        $dH = $iH / 60.0; // H-Prime:    0.0-6.0

        $dX = $dC * (1 - abs(fmod($dH, 2) - 1));

        if ($dH < 0) {
            $dR = 0.0;
            $dG = 0.0;
            $dB = 0.0;
        } elseif ($dH < 1) {
            $dR = $dC;
            $dG = $dX;
            $dB = 0.0;
        } elseif ($dH < 2) {
            $dR = $dX;
            $dG = $dC;
            $dB = 0.0;
        } elseif ($dH < 3) {
            $dR = 0.0;
            $dG = $dC;
            $dB = $dX;
        } elseif ($dH < 4) {
            $dR = 0.0;
            $dG = $dX;
            $dB = $dC;
        } elseif ($dH < 5) {
            $dR = $dX;
            $dG = 0.0;
            $dB = $dC;
        } elseif ($dH < 6) {
            $dR = $dC;
            $dG = 0.0;
            $dB = $dX;
        } else {
            $dR = 0.0;
            $dG = 0.0;
            $dB = 0.0;
        }

        $dM = $dV - $dC;
        $dR += $dM;
        $dG += $dM;
        $dB += $dM;
        $dR *= 255;
        $dG *= 255;
        $dB *= 255;

        return [round($dR), round($dG), round($dB)];
    }
}


if (!function_exists('RGBtoHEX')) {

    /**
     * Prints a RGB number as hex value valid for HTML
     *
     * @param int|array $iR The red value in the range from 0 to 255. Instead of a
     *                      single value an array holding all parameters at once can
     *                      be used.
     * @param int $iG The green value in the range from 0 to 255.
     * @param int $iB The blue value in the range from 0 to 255.
     * @param string $sP Optional. The Prefix. Default: #
     *
     * @return string
     */

    function RGBtoHEX($iR, $iG = 0, $iB = 0, $sP = '#')
    {
        if (is_array($iR)) {
            $iG = $iR[1] ?? 0;
            $iB = $iR[2] ?? 0;
            $sP = $iR[3] ?? '#';
            $iR = $iR[0];
        }

        if ($iR < 0)
            $iR = 0;
        if ($iR > 255)
            $iR = 255;
        if ($iG < 0)
            $iG = 0;
        if ($iG > 255)
            $iG = 255;
        if ($iB < 0)
            $iB = 0;
        if ($iB > 255)
            $iB = 255;

        $sR = str_pad(dechex($iR), 2, '0', STR_PAD_LEFT);
        $sG = str_pad(dechex($iG), 2, '0', STR_PAD_LEFT);
        $sB = str_pad(dechex($iB), 2, '0', STR_PAD_LEFT);

        return $sP . $sR . $sG . $sB;
    }
}

if (!function_exists('replace_umlaut')) {
    function replace_umlaut($text)
    {
        return str_replace([
            'ä',
            'ö',
            'ü',
            'ß',
            'Ä',
            'Ö',
            'Ü'
        ], [
            'ae',
            'oe',
            'ue',
            'ss',
            'Ae',
            'Oe',
            'Ue'
        ], $text ?? '');
    }
}

if (!function_exists('check_rights')) {
    function check_rights($rights, $write, Project $project = null, User $user = null)
    {
        if (is_null($user)) {
            /** @var $user \App\Models\User */
            $user = Auth::user();
        }
        if (is_null($user))
            return false;

        if (!is_array($rights))
            $rights = [$rights];

        if ($write)
            return $user->can_write($rights, $project);
        else
            return $user->can_read($rights, $project);
    }
}

if (!function_exists('check_read')) {
    function check_read($rights, Project $project = null, User $user = null)
    {
        return check_rights($rights, false, $project, $user);
    }
}

if (!function_exists('check_write')) {
    function check_write($rights, Project $project = null, User $user = null)
    {
        return check_rights($rights, true, $project, $user);
    }
}

if (!function_exists('has_project')) {
    function has_project(Project $project = null, User $user = null)
    {
        if (is_null($user)) {
            /** @var $user \App\Models\User */
            $user = Auth::user();
        }
        if (is_null($user))
            return false;

        return $user->has_project($project);
    }
}

if (!function_exists('text_format')) {
    function text_format($text)
    {
        $text = e($text);
        $text = Str::inlineMarkdown($text);
        $text = preg_replace_callback('/\[([^\|\]]*)\|?(.*)\]/', fn($m) => '<a href="' . $m[1] . '">' . (empty($m[2]) ? $m[1] : $m[2]) . '</a>', $text);
        $text = str_replace('<a href=', '<a class="inline-flex items-center underline hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 text-gray-600 dark:text-gray-400" href=', $text);
        $text = trim($text);
        $text = nl2br($text);

        return $text;
    }
}

if (!function_exists('text_code_hint')) {
    function text_code_hint()
    {
        $hint = '<p>';
        $hint .= e(__('Add an ingredient by using:'));
        $hint .= ' <span class="font-black">[' . e(__('REFERENCE')) . ']</span>';
        $hint .= '</p><p>';
        $hint .= e(__('Add an amount by using:'));
        $hint .= ' <span class="font-black">[123]</span> ';
        $hint .= e(__('or'));
        $hint .= ' <span class="font-black">[0.25]</span> ';
        $hint .= e(__('or'));
        $hint .= ' <span class="font-black">[1/4]</span>';
        $hint .= '</p><p>';
        $hint .= e(__('Add a fixed number by using:'));
        $hint .= ' <span class="font-black">[123!]</span> ';
        $hint .= e(__('or'));
        $hint .= ' <span class="font-black">[0.25!]</span> ';
        $hint .= e(__('or'));
        $hint .= ' <span class="font-black">[1/4!]</span>';
        $hint .= '</p><p>';
        $hint .= e(__('Add temperature by using:'));
        $hint .= ' <span class="font-black">[T180]</span>';
        $hint .= '</p><p>';
        $hint .= e(__('Add an icon by using:'));
        $hint .= ' <span class="font-black">:icon:</span>';
        $hint .= '</p><p>';
        $hint .= e(__('Add a Thermomix instruction by using:'));
        $hint .= ' <span class="font-black">~time/temp/speed~</span>';
        $hint .= '</p><p>';
        $hint .= e(__('Change color by using:'));
        $hint .= ' <span class="font-black">{' . e(__('color')) . '}</span>';
        $hint .= '</p><p>';
        $hint .= Blade::render('<x-link route="textcode" target="_blank">' . e(__('For more information click here')) . '</x-link>');
        $hint .= '</p>';


        return $hint;
    }
}

if (!function_exists('text_code_icons')) {
    function text_code_icons()
    {
        $icons = [
            'airplane',
            'alarm',
            'anchor',
            'tm5',
            'tm6',
            'tm-dough',
            'tm-stir',
            'tm-rev'
        ];

        sort($icons);

        return $icons;
    }
}

if (!function_exists('text_code_colors')) {
    function text_code_colors()
    {
        $colors = [
            'red' => 'text-red-500',
            'green' => 'text-green-500',
            'blue' => 'text-blue-500',
        ];

        ksort($colors);

        return $colors;
    }
}

if (!function_exists('text_code_format')) {
    function text_code_format($text, $ingredients = [], $factor = 1, $temp = 'C')
    {
        $text = e($text);

        $text = preg_replace_callback('/~(?:(\d+(?:-\d+)?M?)(?:\/(\d+|V))?\/)?(-?[0-9DS\.]+)~/', function ($matches) {
            $time = $matches[1];
            $temp = $matches[2];
            $speed = $matches[3];

            $text = [];

            if ($time == '') {
                $t1 = 0;
                $t2 = 0;
            } else {
                preg_match('/(\d+)(?:-(\d+))?(M?)/', $time, $mtime);
                $t1 = intval($mtime[1]);
                $t2 = intval($mtime[2]);
                if ($mtime[3] == 'M') {
                    $t1 *= 60;
                    $t2 *= 60;
                }
            }
            if ($t1 > $t2) {
                $tt = $t1;
                $t1 = $t2;
                $t2 = $tt;
            }
            if ($t1 == $t2)
                $t1 = 0;

            if ($t2 != 0) {
                if ($t1 == 0) {
                    $h = intval($t2 / 3600);
                    $m = intval(($t2 / 60) % 60);
                    $s = intval($t2 % 60);
                    $t2 = [];
                    if ($h)
                        array_push($t2, $h . ' ' . __('hour'));
                    if ($m)
                        array_push($t2, $m . ' ' . __('min'));
                    if ($s)
                        array_push($t2, $s . ' ' . __('sec'));
                    array_push($text, implode(' ', $t2));
                } else {
                    $h1 = intval($t1 / 3600);
                    $m1 = intval(($t1 / 60) % 60);
                    $s1 = intval($t1 % 60);
                    $h2 = intval($t2 / 3600);
                    $m2 = intval(($t2 / 60) % 60);
                    $s2 = intval($t2 % 60);
                    $p = 0;
                    $h = $h1 != 0 || $h2 != 0;
                    if ($h)
                        $p++;
                    $m = $m1 != 0 || $m2 != 0;
                    if ($m)
                        $p++;
                    $s = $s1 != 0 || $s2 != 0;
                    if ($s)
                        $p++;

                    if ($p > 1) {
                        $t1 = [];
                        if ($h1)
                            array_push($t1, $h1 . ' ' . __('hour'));
                        if ($m1)
                            array_push($t1, $m1 . ' ' . __('min'));
                        if ($s1)
                            array_push($t1, $s1 . ' ' . __('sec'));
                        $t2 = [];
                        if ($h2)
                            array_push($t2, $h2 . ' ' . __('hour'));
                        if ($m2)
                            array_push($t2, $m2 . ' ' . __('min'));
                        if ($s2)
                            array_push($t2, $s2 . ' ' . __('sec'));
                    } else {
                        $t1 = [];
                        if ($h1)
                            array_push($t1, $h1);
                        if ($m1)
                            array_push($t1, $m1);
                        if ($s1)
                            array_push($t1, $s1);
                        $t2 = [];
                        if ($h2)
                            array_push($t2, $h2 . ' ' . __('hour'));
                        if ($m2)
                            array_push($t2, $m2 . ' ' . __('min'));
                        if ($s2)
                            array_push($t2, $s2 . ' ' . __('sec'));
                    }

                    array_push($text, implode(' ', $t1) . ' - ' . implode(' ', $t2));
                }
            }

            if ($temp == 'V') {
                array_push($text, __('Varoma'));
            } else {
                $temp = intval($temp);
                if ($temp != 0)
                    array_push($text, '[T' . $temp . ']');
            }

            if ($speed != '') {
                preg_match('/(-?)([0-9DS\.]+)/', $speed, $mspeed);
                $r = $mspeed[1];
                $s = $mspeed[2];

                if ($r == '-')
                    array_push($text, ':tm-rev:');

                if ($s == 'D')
                    array_push($text, ':tm-dough:');
                elseif ($s == 'S')
                    array_push($text, __('speed') . ' :tm-stir:');
                else {
                    $s = floatval($s);
                    $s = $s * 2;
                    $s = round($s);
                    $s = $s / 2;
                    if ($s != 0)
                        array_push($text, __('speed') . ' ' . $s);
                }

            }

            $text = implode('/', $text);
            if ($text != '')
                $text = '<span class="thermomix font-bold">' . $text . '</span>';
            return $text;
        }, $text);


        $text = preg_replace_callback('/\[T(\d+?)\]/', function ($matches) use ($temp) {
            $t = intval($matches[1]);
            if ($temp == 'F')
                $t = calculate_round($t * 9 / 5 + 32) . '°F';
            else
                $t = calculate_round($t) . '°C';

            return '<span class="temp">' . $t . '</span>';
        }, $text);

        $text = preg_replace_callback('/\[(\d+(?:\.\d+)?(!?))\]/', function ($matches) use ($factor) {
            $n = floatval($matches[1]);
            if ($matches[2] != '!')
                $n *= $factor;
            return '<span class="number">' . calculate_round($n) . '</span>';
        }, $text);

        $text = preg_replace_callback('/\[(\d+)\/(\d+)(!?)\]/', function ($matches) use ($factor) {
            $n1 = intval($matches[1]);
            $n2 = intval($matches[2]);
            if ($matches[3] != '!')
                $n1 *= $factor;
            if ($n2 == 0)
                return '<span class="number">0</span>';
            return '<span class="number">' . calculate_fraction($n1 / $n2) . '</span>';
        }, $text);

        $text = preg_replace_callback('/\[([a-z]+\d+)\]/', function ($matches) use ($ingredients) {
            $i = $matches[1];
            if (isset($ingredients[$i]))
                return '<span class="ingredient transition-colors" x-orig="' . $i . '">' . $ingredients[$i] . '</span>';
            else
                return '<span class="ingredient transition-colors" x-orig="' . $i . '"><i>' . $i . '</i></span>';
        }, $text);


        $text = trim($text);
        $text = nl2br($text);

        $colors = text_code_colors();
        $spans = 0;
        $text = preg_replace_callback('/\{([a-z]+|-)\}/', function ($matches) use ($colors, &$spans) {

            $text = '';
            if ($matches[1] == '-') {
                $text = str_repeat('</span>', $spans);
                $spans = 0;
            } else {
                if (isset($colors[$matches[1]])) {
                    $text = str_repeat('</span>', $spans);
                    $text .= '<span class="' . $colors[$matches[1]] . '">';
                    $spans = 1;
                }
            }

            return $text;

        }, $text);


        foreach (text_code_icons() as $icon) {
            $text = str_replace(':' . $icon . ':', Blade::render('<x-recipe-icon  name="' . $icon . '" />'), $text);
        }

        return $text;
    }
}

if (!function_exists('calculate_number')) {
    function calculate_number($v, $frac = false)
    {
        if ($frac)
            return calculate_fraction($v);
        else
            return calculate_round($v);
    }
}

if (!function_exists('calculate_fraction')) {
    function calculate_fraction($v)
    {

        if ($v == 0)
            return '0';

        $h1 = 1;
        $h2 = 0;
        $k1 = 0;
        $k2 = 1;
        $b = 1 / $v;
        do {
            $b = 1 / $b;
            $a = floor($b);
            $aux = $h1;
            $h1 = $a * $h1 + $h2;
            $h2 = $aux;
            $aux = $k1;
            $k1 = $a * $k1 + $k2;
            $k2 = $aux;
            $b = $b - $a;
        } while (abs($v - $h1 / $k1) > $v * 0.001); //0.001  = tolerance

        if ($k1 == 1)
            return $h1;

        if ($k1 > 100) {
            return calculate_round($v);
        }

        $f1 = 0;
        while ($h1 > $k1) {
            $f1++;
            $h1 -= $k1;
        }

        $ret = '';
        if ($f1 > 0)
            $ret .= $f1 . ' ';
        $ret .= '<span class="diagonal-fractions">' . $h1 . '/' . $k1 . '</span>';
        return $ret;

    }
}

if (!function_exists('calculate_round')) {
    function calculate_round($v)
    {

        if ($v == 0)
            $v = 0;

        if ($v > 10)
            $v = round($v, 0);

        if ($v > 1)
            $v = round($v, 1);

        $v = round($v, 2);

        if ($v == 0)
            $v = 0.01;

        if ($v * 100 % 100 > 0)
            $nf = number_format($v, 2, ',', '.');
        elseif ($v * 10 % 10 > 0)
            $nf = number_format($v, 1, ',', '.');
        else
            $nf = number_format($v, 0, ',', '.');
        return $nf;

    }
}

if (!function_exists('calculate_time')) {
    function calculate_time($t)
    {
        $h = intval($t / 60);
        $m = intval($t % 60);
        $t2 = [];
        if ($h)
            array_push($t2, $h . ' ' . __('hours'));
        if ($m)
            array_push($t2, $m . ' ' . __('minutes'));
        return implode(' ', $t2);
    }
}

if (!function_exists('IsSlotEmpty')) {
    function IsSlotEmpty(\Illuminate\View\ComponentSlot $slot): bool
    {
        if ($slot->isEmpty())
            return true;
        $content = $slot->toHtml();
        // Remove comments
        $content = preg_replace('/<!--.+?-->/', '', $content);
        // Trim data
        $content = trim($content);

        if ($content === '')
            return true;

        return false;
    }
}

if (!function_exists('default_colors')) {
    function default_colors()
    {
        //Based on the Web colors of CSS3
        return [
            //Gray and black
            ['name' => __('Black'), 'value' => '#000000'],
            ['name' => __('DarkSlateGray'), 'value' => '#2f4f4f'],
            ['name' => __('DimGray'), 'value' => '#696969'],
            ['name' => __('SlateGray'), 'value' => '#708090'],
            ['name' => __('Gray'), 'value' => '#808080'],
            ['name' => __('LightSlateGray'), 'value' => '#778899'],
            ['name' => __('DarkGray'), 'value' => '#a9a9a9'],
            ['name' => __('Silver'), 'value' => '#c0c0c0'],
            ['name' => __('LightGray'), 'value' => '#d3d3d3'],
            ['name' => __('Gainsboro'), 'value' => '#dcdcdc'],
            //White
            ['name' => __('MistyRose'), 'value' => '#ffe4e1'],
            ['name' => __('AntiqueWhite'), 'value' => '#faebd7'],
            ['name' => __('Linen'), 'value' => '#faf0e6'],
            ['name' => __('Beige'), 'value' => '#f5f5dc'],
            ['name' => __('WhiteSmoke'), 'value' => '#f5f5f5'],
            ['name' => __('LavenderBlush'), 'value' => '#fff0f5'],
            ['name' => __('OldLace'), 'value' => '#fdf5e6'],
            ['name' => __('AliceBlue'), 'value' => '#f0f8ff'],
            ['name' => __('Seashell'), 'value' => '#fff5ee'],
            ['name' => __('GhostWhite'), 'value' => '#f8f8ff'],
            ['name' => __('Honeydew'), 'value' => '#f0fff0'],
            ['name' => __('FloralWhite'), 'value' => '#fffaf0'],
            ['name' => __('Azure'), 'value' => '#f0ffff'],
            ['name' => __('MintCream'), 'value' => '#f5fffa'],
            ['name' => __('Snow'), 'value' => '#fffafa'],
            ['name' => __('Ivory'), 'value' => '#fffff0'],
            ['name' => __('White'), 'value' => '#ffffff'],
            //Red
            ['name' => __('DarkRed'), 'value' => '#8b0000'],
            ['name' => __('Red'), 'value' => '#ff0000'],
            ['name' => __('Firebrick'), 'value' => '#b22222'],
            ['name' => __('Crimson'), 'value' => '#dc143c'],
            ['name' => __('IndianRed'), 'value' => '#cd5c5c'],
            ['name' => __('LightCoral'), 'value' => '#f08080'],
            ['name' => __('Salmon'), 'value' => '#fa8072'],
            ['name' => __('DarkSalmon'), 'value' => '#e9967a'],
            ['name' => __('LightSalmon'), 'value' => '#ffa07a'],
            //Orange
            ['name' => __('OrangeRed'), 'value' => '#ff4500'],
            ['name' => __('Tomato'), 'value' => '#ff6347'],
            ['name' => __('DarkOrange'), 'value' => '#ff8c00'],
            ['name' => __('Coral'), 'value' => '#ff7f50'],
            ['name' => __('Orange'), 'value' => '#ffa500'],
            //Yellow
            ['name' => __('DarkKhaki'), 'value' => '#bdb76b'],
            ['name' => __('Gold'), 'value' => '#ffd700'],
            ['name' => __('Khaki'), 'value' => '#f0e68c'],
            ['name' => __('PeachPuff'), 'value' => '#ffdab9'],
            ['name' => __('Yellow'), 'value' => '#ffff00'],
            ['name' => __('PaleGoldenrod'), 'value' => '#eee8aa'],
            ['name' => __('Moccasin'), 'value' => '#ffe4b5'],
            ['name' => __('PapayaWhip'), 'value' => '#ffefd5'],
            ['name' => __('LightGoldenrodYellow'), 'value' => '#fafad2'],
            ['name' => __('LemonChiffon'), 'value' => '#fffacd'],
            ['name' => __('LightYellow'), 'value' => '#ffffe0'],
            //Brown
            ['name' => __('Maroon'), 'value' => '#800000'],
            ['name' => __('Brown'), 'value' => '#a52a2a'],
            ['name' => __('SaddleBrown'), 'value' => '#8b4513'],
            ['name' => __('Sienna'), 'value' => '#a0522d'],
            ['name' => __('Chocolate'), 'value' => '#d2691e'],
            ['name' => __('DarkGoldenrod'), 'value' => '#b8860b'],
            ['name' => __('Peru'), 'value' => '#cd853f'],
            ['name' => __('RosyBrown'), 'value' => '#bc8f8f'],
            ['name' => __('Goldenrod'), 'value' => '#daa520'],
            ['name' => __('SandyBrown'), 'value' => '#f4a460'],
            ['name' => __('Tan'), 'value' => '#d2b48c'],
            ['name' => __('Burlywood'), 'value' => '#deb887'],
            ['name' => __('Wheat'), 'value' => '#f5deb3'],
            ['name' => __('NavajoWhite'), 'value' => '#ffdead'],
            ['name' => __('Bisque'), 'value' => '#ffe4c4'],
            ['name' => __('BlanchedAlmond'), 'value' => '#ffebcd'],
            ['name' => __('Cornsilk'), 'value' => '#fff8dc'],
            //Pink
            ['name' => __('MediumVioletRed'), 'value' => '#c71585'],
            ['name' => __('DeepPink'), 'value' => '#ff1493'],
            ['name' => __('PaleVioletRed'), 'value' => '#db7093'],
            ['name' => __('HotPink'), 'value' => '#ff69b4'],
            ['name' => __('LightPink'), 'value' => '#ffb6c1'],
            ['name' => __('Pink'), 'value' => '#ffc0cb'],
            //Purple
            ['name' => __('Indigo'), 'value' => '#4b0082'],
            ['name' => __('Purple'), 'value' => '#800080'],
            ['name' => __('DarkMagenta'), 'value' => '#8b008b'],
            ['name' => __('DarkViolet'), 'value' => '#9400d3'],
            ['name' => __('DarkSlateBlue'), 'value' => '#483d8b'],
            ['name' => __('BlueViolet'), 'value' => '#8a2be2'],
            ['name' => __('DarkOrchid'), 'value' => '#9932cc'],
            ['name' => __('Fuchsia'), 'value' => '#ff00ff'],
            ['name' => __('Magenta'), 'value' => '#ff00ff'],
            ['name' => __('SlateBlue'), 'value' => '#6a5acd'],
            ['name' => __('MediumSlateBlue'), 'value' => '#7b68ee'],
            ['name' => __('MediumOrchid'), 'value' => '#ba55d3'],
            ['name' => __('MediumPurple'), 'value' => '#9370db'],
            ['name' => __('Orchid'), 'value' => '#da70d6'],
            ['name' => __('Violet'), 'value' => '#ee82ee'],
            ['name' => __('Plum'), 'value' => '#dda0dd'],
            ['name' => __('Thistle'), 'value' => '#d8bfd8'],
            ['name' => __('Lavender'), 'value' => '#e6e6fa'],
            //Blue
            ['name' => __('MidnightBlue'), 'value' => '#191970'],
            ['name' => __('Navy'), 'value' => '#000080'],
            ['name' => __('DarkBlue'), 'value' => '#00008b'],
            ['name' => __('MediumBlue'), 'value' => '#0000cd'],
            ['name' => __('Blue'), 'value' => '#0000ff'],
            ['name' => __('RoyalBlue'), 'value' => '#4169e1'],
            ['name' => __('SteelBlue'), 'value' => '#4682b4'],
            ['name' => __('DodgerBlue'), 'value' => '#1e90ff'],
            ['name' => __('DeepSkyBlue'), 'value' => '#00bfff'],
            ['name' => __('CornflowerBlue'), 'value' => '#6495ed'],
            ['name' => __('SkyBlue'), 'value' => '#87ceeb'],
            ['name' => __('LightSkyBlue'), 'value' => '#87cefa'],
            ['name' => __('LightSteelBlue'), 'value' => '#b0c4de'],
            ['name' => __('LightBlue'), 'value' => '#add8e6'],
            ['name' => __('PowderBlue'), 'value' => '#b0e0e6'],
            //Cyan
            ['name' => __('Teal'), 'value' => '#008080'],
            ['name' => __('DarkCyan'), 'value' => '#008b8b'],
            ['name' => __('LightSeaGreen'), 'value' => '#20b2aa'],
            ['name' => __('CadetBlue'), 'value' => '#5f9ea0'],
            ['name' => __('DarkTurquoise'), 'value' => '#00ced1'],
            ['name' => __('MediumTurquoise'), 'value' => '#48d1cc'],
            ['name' => __('Turquoise'), 'value' => '#40e0d0'],
            ['name' => __('Aqua'), 'value' => '#00ffff'],
            ['name' => __('Cyan'), 'value' => '#00ffff'],
            ['name' => __('Aquamarine'), 'value' => '#7fffd4'],
            ['name' => __('PaleTurquoise'), 'value' => '#afeeee'],
            ['name' => __('LightCyan'), 'value' => '#e0ffff'],
            //Green
            ['name' => __('DarkGreen'), 'value' => '#006400'],
            ['name' => __('Green'), 'value' => '#008000'],
            ['name' => __('DarkOliveGreen'), 'value' => '#556b2f'],
            ['name' => __('ForestGreen'), 'value' => '#228b22'],
            ['name' => __('SeaGreen'), 'value' => '#2e8b57'],
            ['name' => __('Olive'), 'value' => '#808000'],
            ['name' => __('OliveDrab'), 'value' => '#6b8e23'],
            ['name' => __('MediumSeaGreen'), 'value' => '#3cb371'],
            ['name' => __('LimeGreen'), 'value' => '#32cd32'],
            ['name' => __('Lime'), 'value' => '#00ff00'],
            ['name' => __('SpringGreen'), 'value' => '#00ff7f'],
            ['name' => __('MediumSpringGreen'), 'value' => '#00fa9a'],
            ['name' => __('DarkSeaGreen'), 'value' => '#8fbc8f'],
            ['name' => __('MediumAquamarine'), 'value' => '#66cdaa'],
            ['name' => __('YellowGreen'), 'value' => '#9acd32'],
            ['name' => __('LawnGreen'), 'value' => '#7cfc00'],
            ['name' => __('Chartreuse'), 'value' => '#7fff00'],
            ['name' => __('LightGreen'), 'value' => '#90ee90'],
            ['name' => __('GreenYellow'), 'value' => '#adff2f'],
            ['name' => __('PaleGreen'), 'value' => '#98fb98'],
        ];
    }
}
