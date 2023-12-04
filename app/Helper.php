<?php

namespace App;

use Cache;
use Image;
use App\Models\User;
use App\Models\Pages;
use App\Models\Notifications;
use App\Models\LiveStreamings;
use App\Models\PaymentGateways;
use Illuminate\Support\Facades\Storage;
use Phattarachai\LaravelMobileDetect\Agent;

class Helper
{
	// spaces
	public static function spacesUrlFiles($string)
	{
		return (preg_replace('/(\s+)/u', '_', $string));
	}

	public static function spacesUrl($string)
	{
		return (preg_replace('/(\s+)/u', '+', trim($string)));
	}

	public static function removeLineBreak($string)
	{
		return str_replace(array("\r\n", "\r"), "", $string);
	}

	public static function lineBreakRemove($string)
	{
		return str_replace(array("\r\n", "\r"), " ", $string);
	}

	// Text With (2) line break
	public static function checkTextDb($str)
	{
		if (mb_strlen($str, 'utf8') < 1) {
			return false;
		}
		$str = preg_replace('/(?:(?:\r\n|\r|\n)\s*){3}/s', "\r\n\r\n", $str);
		$str = trim($str, "\r\n");

		return $str;
	}

	public static function checkText($str, $url = null)
	{
		if (mb_strlen($str, 'utf8') < 1) {
			return false;
		}

		$str = $url ? str_replace($url, '', $str) : $str;
		$str = trim($str);
		$str = nl2br(e($str));
		$str = str_replace('&#039;', "'", $str);

		$str = str_replace(array(chr(10), chr(13)), '', $str);
		$url = preg_replace('#^https?://#', '', url('') . '/');

		// Hashtags and @Mentions
		$str = preg_replace_callback(
			'~([#@])([^\s#@!\"\$\%&\'\(\)\*\+\,\./\:\;\<\=\>?\[/\/\/\\]\^\`\{\|\}\~]+)~',
			function ($matches) use ($url) {
				$url = $matches[1] == "#" ? "" . $url . "explore?q=%23" . $matches[2] . "" : $url . $matches[2];
				return "<a href=\"//" . $url . "\">$matches[0]</a>";
			},
			$str
		);

		$str = stripslashes($str);
		return $str;
	}

	public static function formatNumber($number)
	{
		if ($number >= 1000 &&  $number < 1000000) {

			return number_format($number / 1000, 1) . "k";
		} else if ($number >= 1000000) {
			return number_format($number / 1000000, 1) . "M";
		} else {
			return $number;
		}
	} //<<<<--- End Function

	public static function formatNumbersStats($number)
	{
		if ($number >= 100000000) {
			return '<span class="counterStats">' . number_format($number / 1000000, 0) . "</span>M";
		} else {
			return '<span class="counterStats">' . number_format($number) . '</span>';
		}
	} //<<<<--- End Function

	public static function spaces($string)
	{
		return (preg_replace('/(\s+)/u', ' ', $string));
	}

	public static function resizeImage($image, $width, $height, $scale, $imageNew = null)
	{
		list($imagewidth, $imageheight, $imageType) = getimagesize($image);
		$imageType = image_type_to_mime_type($imageType);
		$newImageWidth = ceil($width * $scale);
		$newImageHeight = ceil($height * $scale);
		$newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
		switch ($imageType) {
			case "image/gif":
				$source = imagecreatefromgif($image);
				imagefill($newImage, 0, 0, imagecolorallocate($newImage, 255, 255, 255));
				imagealphablending($newImage, TRUE);
				break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$source = imagecreatefromjpeg($image);
				break;
			case "image/png":
			case "image/x-png":
				$source = imagecreatefrompng($image);
				imagealphablending($newImage, false);
				imagesavealpha($newImage, true);
				break;
		}
		imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $width, $height);

		switch ($imageType) {
			case "image/gif":
				imagegif($newImage, $imageNew);
				break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				imagejpeg($newImage, $imageNew, 90);
				break;
			case "image/png":
			case "image/x-png":
				imagepng($newImage, $imageNew);
				break;
		}

		chmod($image, 0777);
		return $image;
	}

	public static function resizeImageFixed($image, $width, $height, $imageNew = null)
	{
		list($imagewidth, $imageheight, $imageType) = getimagesize($image);
		$imageType = image_type_to_mime_type($imageType);
		$newImage = imagecreatetruecolor($width, $height);

		switch ($imageType) {
			case "image/gif":
				$source = imagecreatefromgif($image);
				imagefill($newImage, 0, 0, imagecolorallocate($newImage, 255, 255, 255));
				imagealphablending($newImage, TRUE);
				break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$source = imagecreatefromjpeg($image);
				break;
			case "image/png":
			case "image/x-png":
				$source = imagecreatefrompng($image);
				imagefill($newImage, 0, 0, imagecolorallocate($newImage, 255, 255, 255));
				imagealphablending($newImage, TRUE);
				break;
		}
		if ($width / $imagewidth > $height / $imageheight) {
			$nw = $width;
			$nh = ($imageheight * $nw) / $imagewidth;
			$px = 0;
			$py = ($height - $nh) / 2;
		} else {
			$nh = $height;
			$nw = ($imagewidth * $nh) / $imageheight;
			$py = 0;
			$px = ($width - $nw) / 2;
		}

		imagecopyresampled($newImage, $source, $px, $py, 0, 0, $nw, $nh, $imagewidth, $imageheight);

		switch ($imageType) {
			case "image/gif":
				imagegif($newImage, $imageNew);
				break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				imagejpeg($newImage, $imageNew, 90);
				break;
			case "image/png":
			case "image/x-png":
				imagepng($newImage, $imageNew);
				break;
		}

		chmod($image, 0777);
		return $image;
	}

	public static function getHeight($image)
	{
		$size   = getimagesize($image);
		$height = $size[1];
		return $height;
	}

	public static function getWidth($image)
	{
		$size  = getimagesize($image);
		$width = $size[0];
		return $width;
	}
	public static function formatBytes($size, $precision = 2)
	{
		$base = log($size, 1024);
		$suffixes = array('', 'kB', 'MB', 'GB', 'TB');

		return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
	}

	public static function removeHTPP($string)
	{
		$string = preg_replace('#^https?://#', '', $string);
		return $string;
	}

	public static function Array2Str($kvsep, $entrysep, $a)
	{
		$str = "";
		foreach ($a as $k => $v) {
			$str .= "{$k}{$kvsep}{$v}{$entrysep}";
		}
		return $str;
	}

	public static function removeBR($string)
	{
		$html    = preg_replace('[^(<br( \/)?>)*|(<br( \/)?>)*$]', '', $string);
		$output = preg_replace('~(?:<br\b[^>]*>|\R){3,}~i', '<br /><br />', $html);
		return $output;
	}

	public static function removeTagScript($html)
	{
		//parsing begins here:
		$doc = new \DOMDocument();
		@$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
		$nodes = $doc->getElementsByTagName('script');

		$remove = [];

		foreach ($nodes as $item) {
			$remove[] = $item;
		}

		foreach ($remove as $item) {
			$item->parentNode->removeChild($item);
		}

		return preg_replace(
			'/^<!DOCTYPE.+?>/',
			'',
			str_replace(
				array('<html>', '</html>', '<body>', '</body>', '<head>', '</head>', '<p>', '</p>', '&nbsp;'),
				array('', '', '', '', '', ' '),
				$doc->saveHtml()
			)
		);
	} // End Method

	public static function removeTagIframe($html)
	{
		//parsing begins here:
		$doc = new \DOMDocument();
		@$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
		$nodes = $doc->getElementsByTagName('iframe');

		$remove = [];

		foreach ($nodes as $item) {
			$remove[] = $item;
		}

		foreach ($remove as $item) {
			$item->parentNode->removeChild($item);
		}

		return preg_replace(
			'/^<!DOCTYPE.+?>/',
			'',
			str_replace(
				array('<html>', '</html>', '<body>', '</body>', '<head>', '</head>', '<p>', '</p>', '&nbsp;'),
				array('', '', '', '', '', ' '),
				$doc->saveHtml()
			)
		);
	} // End Method

	public static function fileNameOriginal($string)
	{
		return pathinfo($string, PATHINFO_FILENAME);
	}

	public static function formatDate($date, $time = false)
	{
		if ($time == false) {
			$date = strtotime($date);
		}

		$day    = date('d', $date);
		$_month = date('m', $date);
		$month  = __("months.$_month");
		$year   = date('Y', $date);

		if (config('settings.date_format') == 'M d, Y') {
			$dateFormat = $month . ' ' . $day . ', ' . $year;
		} elseif (config('settings.date_format') == 'd M, Y') {
			$dateFormat = $day . ' ' . $month . ', ' . $year;
		} else {
			$dateFormat = date(config('settings.date_format'), $date);
		}

		return $dateFormat;
	}

	public static function watermark($name, $watermarkSource)
	{
		$thumbnail = Image::make($name);
		$watermark = Image::make($watermarkSource);
		$x = 0;

		while ($x < $thumbnail->width()) {
			$y = 0;

			while ($y < $thumbnail->height()) {
				$thumbnail->insert($watermarkSource, 'top-left', $x, $y);
				$y += $watermark->height();
			}

			$x += $watermark->width();
		}

		$thumbnail->save($name)->destroy();
	}

	public static function amountFormat($value)
	{
		switch (config('settings.currency_position')) {
			case 'left':
				$amount = config('settings.currency_symbol') . number_format($value);
				break;

			case 'left_space':
				$amount = config('settings.currency_symbol') . ' ' . number_format($value);
				break;

			case 'right':
				$amount = number_format($value) . config('settings.currency_symbol');
				break;

			case 'right_space':
				$amount = number_format($value) . ' ' . config('settings.currency_symbol');
				break;

			default:
				$amount = config('settings.currency_symbol') . number_format($value);
				break;
		}

		return $amount;
	}

	public static function amountWithoutFormat($value)
	{
		switch (config('settings.currency_position')) {
			case 'left':
				$amount = config('settings.currency_symbol') . $value;
				break;

			case 'left_space':
				$amount = config('settings.currency_symbol') . ' ' . $value;
				break;

			case 'right':
				$amount = $value . config('settings.currency_symbol');
				break;

			case 'right_space':
				$amount = $value . ' ' . config('settings.currency_symbol');
				break;

			default:
				$amount = config('settings.currency_symbol') . $value;
				break;
		}

		return $amount;
	}

	public static function getYoutubeId($url)
	{
		$pattern =
			'%^# Match any youtube URL
			(?:https?://)?
			(?:www\.)?
			(?:
				youtu\.be/
			| youtube\.com
				(?:
					/embed/
				| /v/
				| .*v=
				)
			)
			([\w-]{10,12})
			($|&).*
			$%x';

		$result = preg_match($pattern, $url, $matches);
		if ($matches) {
			return $matches[1];
		}
		return false;
	} //<<<-- End

	public static function getVimeoId($url)
	{
		$url = explode('/', $url);
		$slashCount =  count($url) - 1; // added by AMR
		$trail = ''; // added by AMR until dev finds solution
		if ($slashCount == 4) {
			$trail = '?h=' . $url[4];
		}
		return $url[3] . $trail;
	}

	public static function videoUrl($url)
	{
		$urlValid = filter_var($url, FILTER_VALIDATE_URL) ? true : false;

		if ($urlValid) {
			$parse = parse_url($url);
			$host  = strtolower($parse['host']);

			if ($host) {
				if (in_array($host, array(
					'youtube.com',
					'www.youtube.com',
					'm.youtube.com',
					'youtu.be',
					'www.youtu.be',
					'vimeo.com',
					'player.vimeo.com'
				))) {
					return $host;
				}
			}
		}
	}

	//============== linkText
	public static function linkText($text)
	{
		return preg_replace('/https?:\/\/[\w\-\.!~#?&=+%;:\*\'"(),\/]+/u', '<a class="data-link" href="$0" target="_blank">$0</a>', $text);
	}

	public static function strRandom()
	{
		return substr(strtolower(md5(time() . mt_rand(1000, 9999))), 0, 8);
	} // End method

	public static function amountFormatDecimal($value, $applyTax = null)
	{
		// Apply Taxes
		if (auth()->check() && $applyTax) {
			$isTaxable = auth()->user()->isTaxable();
			$taxes = 0;

			if ($applyTax && $isTaxable->count()) {
				foreach ($isTaxable as $tax) {
					$taxes += $tax->percentage;
				}

				$valueWithTax = number_format($taxes * $value / 100, 2, '.', '');
				$value = ($value + $valueWithTax);
			}
		} // isTaxable

		if (config('settings.currency_code') == 'JPY') {
			return config('settings.currency_symbol') . number_format($value);
		}

		if (config('settings.decimal_format') == 'dot') {
			$decimalDot = '.';
			$decimalComma = ',';
		} else {
			$decimalDot = ',';
			$decimalComma = '.';
		}

		switch (config('settings.currency_position')) {
			case 'left':
				$amount = config('settings.currency_symbol') . number_format($value, 2, $decimalDot, $decimalComma);
				break;

			case 'left_space':
				$amount = config('settings.currency_symbol') . ' ' . number_format($value, 2, $decimalDot, $decimalComma);
				break;

			case 'right':
				$amount = number_format($value, 2, $decimalDot, $decimalComma) . config('settings.currency_symbol');
				break;

			case 'right_space':
				$amount = number_format($value, 2, $decimalDot, $decimalComma) . ' ' . config('settings.currency_symbol');
				break;

			default:
				$amount = config('settings.currency_symbol') . number_format($value, 2, $decimalDot, $decimalComma);
				break;
		}

		return $amount;
	} // END

	public static function calculateProductPriceOnStore($value, $shippingFee = 0.00)
	{
		// Aplly Taxes
		if (auth()->check()) {
			$isTaxable = auth()->user()->isTaxable();
			$taxes = 0;

			if ($isTaxable->count()) {
				foreach ($isTaxable as $tax) {
					$taxes += $tax->percentage;
				}

				$valueWithTax = number_format($taxes * $value / 100, 2, '.', '');
				$value = ($value + $valueWithTax);
			}
		} // isTaxable

		if (config('settings.currency_code') == 'JPY') {
			return config('settings.currency_symbol') . number_format($value);
		}

		if (config('settings.decimal_format') == 'dot') {
			$decimalDot = '.';
			$decimalComma = ',';
		} else {
			$decimalDot = ',';
			$decimalComma = '.';
		}

		$value = ($value + $shippingFee);

		switch (config('settings.currency_position')) {
			case 'left':
				$amount = config('settings.currency_symbol') . number_format($value, 2, $decimalDot, $decimalComma);
				break;

			case 'left_space':
				$amount = config('settings.currency_symbol') . ' ' . number_format($value, 2, $decimalDot, $decimalComma);
				break;

			case 'right':
				$amount = number_format($value, 2, $decimalDot, $decimalComma) . config('settings.currency_symbol');
				break;

			case 'right_space':
				$amount = number_format($value, 2, $decimalDot, $decimalComma) . ' ' . config('settings.currency_symbol');
				break;

			default:
				$amount = config('settings.currency_symbol') . number_format($value, 2, $decimalDot, $decimalComma);
				break;
		}

		return $amount;
	} // End calculateProductPriceOnStore

	public static function amountGrossProductShop($amount, $shippingFee = 0.00)
	{
		// Aplly Taxes
		$isTaxable = auth()->user()->isTaxable();
		$taxes = 0;

		if ($isTaxable->count()) {
			foreach ($isTaxable as $tax) {
				$taxes += $tax->percentage;
			}

			$amount = $amount + ($taxes * $amount / 100);

			if (config('settings.currency_code') == 'JPY') {
				$amount = round($amount + $shippingFee);
			} else {
				$amount = number_format($amount + $shippingFee, 2, '.', '');
			}

			return $amount;
		} // isTaxable

		return $amount + $shippingFee;
	} // End amountGrossProductShop

	public static function amountGross($amount)
	{
		// Aplly Taxes
		$isTaxable = auth()->user()->isTaxable();
		$taxes = 0;

		if ($isTaxable->count()) {
			foreach ($isTaxable as $tax) {
				$taxes += $tax->percentage;
			}

			if (config('settings.currency_code') == 'JPY') {
				$amount = round($amount + ($taxes * $amount / 100));
			} else {
				$amount = number_format($amount + ($taxes * $amount / 100), 2, '.', '');
			}

			return $amount;
		} // isTaxable

		return $amount;
	}

	public static function calculatePercentage($value, $percentage)
	{
		return number_format(($value * $percentage / 100), 2);
	}

	public static function envUpdate($key, $value, $comma = false)
	{
		$path = base_path('.env');
		$value = trim($value);
		$env = $comma ? '"' . env($key) . '"' : env($key);

		if (file_exists($path)) {
			file_put_contents($path, str_replace(
				$key . '=' . $env,
				$key . '=' . $value,
				file_get_contents($path)
			));
		}
	}

	public static function urlToDomain($url)
	{
		$domain = explode('/', preg_replace('/https?:\/\/(www\.)?/', '', $url));
		return $domain['0'];
	}

	public static function checkSourceURL($url)
	{
		$urlFrom = strtolower(self::urlToDomain($url));
		$urlServer = self::urlToDomain(url('/'));

		if ($urlFrom != $urlServer) {
			return false;
		}

		return true;
	}

	public static function expandLink($url)
	{
		$headers = get_headers($url, 1);

		if (!empty($headers['Location'])) {
			$headers['Location'] = (array) $headers['Location'];
			$url = array_pop($headers['Location']);
		}
		return $url;
	}

	public static function getFirstUrl($string)
	{
		preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $string, $_matches);
		$firstURL = $_matches[0][0] ?? false;

		if ($firstURL) {
			return $firstURL;
		}
	}

	public static function daysInMonth($month, $year)
	{
		return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
	}

	public static function PercentageIncreaseDecrease($currentPeriod, $previousPeriod)
	{
		if ($currentPeriod > $previousPeriod && $previousPeriod != 0) {
			$subtraction = $currentPeriod  - $previousPeriod;
			$percentage = $subtraction / $currentPeriod * 100;
			return '<small class="float-right text-success">
			 <strong><i class="feather icon-arrow-up mr-1"></i> ' . number_format($percentage, 2) . '%</strong>
			 </small>';
		} elseif ($currentPeriod < $previousPeriod && $currentPeriod != 0) {
			$subtraction = $previousPeriod - $currentPeriod;
			$percentage = $subtraction / $currentPeriod * 100;
			return '<small class="float-right text-danger">
			<strong><i class="feather icon-arrow-down mr-1"></i> ' . number_format($percentage, 2) . '%</strong>
			</small>';
		} elseif ($currentPeriod < $previousPeriod && $previousPeriod != 0) {
			$subtraction = $previousPeriod - $currentPeriod;
			$percentage = $subtraction / $previousPeriod * 100;
			return '<small class="float-right text-danger">
			<strong><i class="feather icon-arrow-down mr-1"></i> ' . number_format($percentage, 2) . '%</strong>
			</small>';
		} elseif ($currentPeriod == $previousPeriod) {
			return '<small class="float-right text-muted">
			<strong>0%</strong>
			</small>';
		} else {
			$percentage = $currentPeriod / 100 * 100;
			return '<small class="float-right text-success">
			<strong><i class="feather icon-arrow-up mr-1"></i> ' . number_format($percentage, 2) . '%</strong>
			</small>';
		}
	} // End method

	public static function percentageIncreaseDecreaseAdmin($currentPeriod, $previousPeriod)
	{
		if ($currentPeriod > $previousPeriod && $previousPeriod != 0) {
			$subtraction = $currentPeriod  - $previousPeriod;
			$percentage = $subtraction / $currentPeriod * 100;
			return '<small class="float-end text-success">
			 <strong><i class="bi bi-arrow-up me-1"></i> ' . number_format($percentage, 2) . '%</strong>
			 </small>';
		} elseif ($currentPeriod < $previousPeriod && $currentPeriod != 0) {
			$subtraction = $previousPeriod - $currentPeriod;
			$percentage = $subtraction / $currentPeriod * 100;
			return '<small class="float-end text-danger">
			<strong><i class="bi bi-arrow-down me-1"></i> ' . number_format($percentage, 2) . '%</strong>
			</small>';
		} elseif ($currentPeriod < $previousPeriod && $previousPeriod != 0) {
			$subtraction = $previousPeriod - $currentPeriod;
			$percentage = $subtraction / $previousPeriod * 100;
			return '<small class="float-end text-danger">
			<strong><i class="bi bi-arrow-down me-1"></i> ' . number_format($percentage, 2) . '%</strong>
			</small>';
		} elseif ($currentPeriod == $previousPeriod) {
			return '<small class="float-end text-muted">
			<strong>0%</strong>
			</small>';
		} else {
			$percentage = $currentPeriod / 100 * 100;
			return '<small class="float-end text-success">
			<strong><i class="bi bi-arrow-up me-1"></i> ' . number_format($percentage, 2) . '%</strong>
			</small>';
		}
	} // End method

	private static function getPool($type = 'alnum')
	{
		switch ($type) {
			case 'alnum':
				$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				break;
			case 'alpha':
				$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				break;
			case 'hexdec':
				$pool = '0123456789abcdef';
				break;
			case 'numeric':
				$pool = '0123456789';
				break;
			case 'nozero':
				$pool = '123456789';
				break;
			case 'distinct':
				$pool = '2345679ACDEFHJKLMNPRSTUVWXYZ';
				break;
			default:
				$pool = (string) $type;
				break;
		}

		return $pool;
	}

	/**
	 * Generate a random secure crypt figure
	 */
	private static function secureCrypt($min, $max)
	{
		$range = $max - $min;

		if ($range < 0) {
			return $min; // not so random...
		}

		$log    = log($range, 2);
		$bytes  = (int) ($log / 8) + 1; // length in bytes
		$bits   = (int) $log + 1; // length in bits
		$filter = (int) (1 << $bits) - 1; // set all lower bits to 1
		do {
			$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
			$rnd = $rnd & $filter; // discard irrelevant bits
		} while ($rnd >= $range);

		return $min + $rnd;
	}

	/**
	 * Finally, generate a hashed token
	 */
	public static function getHashedToken($length = 25)
	{
		$token = "";
		$max   = strlen(static::getPool());
		for ($i = 0; $i < $length; $i++) {
			$token .= static::getPool()[static::secureCrypt(0, $max)];
		}

		return $token;
	}

	public static function genTranxRef()
	{
		return self::getHashedToken();
	}

	// Show Section My Cards
	public static function showSectionMyCards()
	{
		return PaymentGateways::whereName('Stripe')
			->whereEnabled('1')
			->orWhere('name', 'Paystack')
			->whereEnabled('1')
			->first() ? true : false;
	}

	// Get file from Disk
	public static function getFile($path)
	{
		if (env('FILESYSTEM_DRIVER') == 'backblaze') {
			return 'https://' . env('BACKBLAZE_BUCKET') . '.' . env('BACKBLAZE_BUCKET_REGION') . '/' . $path;
		} elseif (env('FILESYSTEM_DRIVER') == 'dospace' && env('DOS_CDN')) {
			return 'https://' . env('DOS_BUCKET') . '.' . env('DOS_DEFAULT_REGION') . '.cdn.digitaloceanspaces.com/' . $path;
		} else {
			return Storage::url($path);
		}
	}

	// User wallet format
	public static function userWallet($balance = null)
	{
		// Get Balance current
		if ($balance) {
			if (config('settings.wallet_format') != 'real_money') {
				return auth()->user()->wallet;
			}

			return auth()->user()->wallet;
		}

		// Format Wallet
		switch (config('settings.wallet_format')) {
			case 'real_money':
				$formatWallet = self::amountFormatDecimal(auth()->user()->wallet);
				break;

			case 'credits':
				$formatWallet = auth()->user()->wallet . ' ' . __('general.credits');
				break;

			case 'points':
				$formatWallet = auth()->user()->wallet . ' ' . __('general.points');
				break;

			case 'tokens':
				$formatWallet = auth()->user()->wallet . ' ' . __('general.tokens');
				break;
		}

		return $formatWallet;
	}

	public static function sizeFileMb($size, $precision = 2)
	{
		$base = log($size, 1024);
		return round(pow(1024, $base - floor($base)), $precision);
	}

	public static function getDatacURL($url)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$ch = curl_exec($ch);

		return json_decode($ch);
	}

	public static function userCountry()
	{
		$ip = request()->ip();
		if (cache('userCountry-' . $ip)) {

			// Give access to Admin or staff if their country has been blocked.
			if (auth()->check() && auth()->user()->permission == 'all') {
				return 'null';
			}

			return cache('userCountry-' . $ip);
		} else {
			return 'null';
		}
	}

	public static function equivalentMoney($walletFormat)
	{
		switch ($walletFormat) {
			case 'credits':
				return __('general.credit_equivalent_money') . ' ' . self::amountFormatDecimal(1) . ' ' . config('settings.currency_code');
				break;

			case 'points':
				return __('general.point_equivalent_money') . ' ' . self::amountFormatDecimal(1) . ' ' . config('settings.currency_code');
				break;

			case 'tokens':
				return __('general.token_equivalent_money') . ' ' . self::amountFormatDecimal(1) . ' ' . config('settings.currency_code');
				break;

			default:
				return false;
		}
	}

	public static function referralLink()
	{
		if (auth()->check() && config('settings.referral_system') == 'on') {

			return '?ref=' . auth()->user()->id;
		}
	}

	public static function pages()
	{
		$pagesLocale  = Pages::whereLang(session('locale'))->orderBy('id')->get();

		if ($pagesLocale->count() <> 0) {
			return $pagesLocale;
		} else {
			return Pages::whereLang(env('DEFAULT_LOCALE'))->orderBy('id')->get();
		}
	}

	public static function liveStatus($id)
	{
		return LiveStreamings::whereId($id)
			->where('updated_at', '>', now()->subMinutes(5))
			->whereStatus('0')
			->first();
	}

	public static function calculateSubscriptionDiscount($interval, $priceMonth, $planPrice)
	{
		switch ($interval) {
			case 'weekly':
				return number_format(((($priceMonth / 4) - $planPrice) / ($priceMonth / 4) * 100), 0);
				break;

			case 'quarterly':
				return number_format(((($priceMonth * 3) - $planPrice) / ($priceMonth * 3) * 100), 0);
				break;

			case 'biannually':
				return number_format(((($priceMonth * 6) - $planPrice) / ($priceMonth * 6) * 100), 0);
				break;

			case 'yearly':
				return number_format(((($priceMonth * 12) - $planPrice) / ($priceMonth * 12) * 100), 0);
				break;
		}
	}

	public static function formatDatepicker($datepicker = false)
	{
		switch (config('settings.date_format')) {
			case 'M d, Y':
				$date = 'm/d/Y';
				$datePickerFormat = 'mm/dd/yyyy';
				break;

			case 'd M, Y':
				$date = 'd/m/Y';
				$datePickerFormat = 'dd/mm/yyyy';
				break;

			case 'Y-m-d':
				$date = 'Y/m/d';
				$datePickerFormat = 'yyyy/mm/dd';
				break;

			case 'm/d/Y':
				$date = 'm/d/Y';
				$datePickerFormat = 'mm/dd/yyyy';
				break;

			case 'd/m/Y':
				$date = 'd/m/Y';
				$datePickerFormat = 'dd/mm/yyyy';
				break;
		}

		return $datepicker ? $datePickerFormat : $date;
	}

	public static function getFileSize($filename)
	{
		$headers  = get_headers($filename, 1);
		$fsize    = $headers['Content-Length'];
		$size    = static::formatBytes($fsize, 1);

		return $size;
	}

	public static function sendNotificationMention($data, $target)
	{
		$post = strtolower($data);
		preg_match_all('~([@])([^\s@!\"\$\%&\'\(\)\*\+\,\./\:\;\<\=\>?\[/\/\/\\]\^\`\{\|\}\~]+)~', $post, $matches);

		foreach ($matches as $key) {
			$key = array_unique($key);
		}

		$numMentions = count($matches[1]);

		for ($i = 0; $i < $numMentions; ++$i) {
			if (isset($key[$i])) {
				$key[$i] = strip_tags($key[$i]);
				/* Verified Username  */
				$user = User::whereUsername(trim($key[$i]))
					->whereNotifyMentions('yes')
					->first();
				if ($user) {
					if ($user->id != auth()->id()) {
						Notifications::send($user->id, auth()->id(), 16, $target);
					}
				}
			}
		} // end for
	} // end method sendNotificationMention

	public static function emojis()
	{
		return [
			'😀', '😃', '😄', '😁', '😆', '😅', '😂', '🤣', '😊', '😇', '🙂', '🙃', '😉', '😌', '😍', '🥰',
			'😘', '😗', '😙', '😚', '😋', '😛', '😝', '😜', '🤪', '🤨', '🧐', '🤓', '😎',
			'🤩', '🥳', '😏', '😒', '😞', '😔', '😟', '😕', '🙁', '☹️', '😣', '😖', '😫',
			'😩', '🥺', '😢', '😭', '😤', '😠', '😡', '🤬', '🤯', '😳', '🥵', '🥶', '😱',
			'😨', '😰', '😥', '😓', '🤗', '🤔', '🤭', '🤫', '🤥', '😶', '😐', '😑', '😬',
			'🙄', '😯', '😦', '😧', '😮', '😲', '🥱', '😴', '🤤', '😪', '😵', '🤐', '🥴', '🤢',
			'🤮', '🤧', '😷', '🤒', '🤕', '🤑', '🤠', '😈', '👿', '👹', '👺', '🤡', '💩', '👻',
			'💀', '☠️', '👽', '👾', '🤖', '🎃', '😺', '😸', '😹', '😻', '😼', '😽', '🙀', '😿', '😾',
			'👋', '🤚', '🖐', '✋', '🖖', '👌', '🤏', '✌️', '🤞', '🤟', '🤘', '🤙', '👈', '👉', '👆', '🖕', '👇',
			'☝️', '👍', '👎', '✊', '👊', '🤛', '🤜', '👏', '🙌', '👐', '🤲', '🤝', '🙏', '✍️', '💅', '🤳',
			'💪', '🦾', '🦵', '🦿', '🦶', '👣', '👂', '🦻', '👃', '🧠', '🦷', '🦴', '👀', '👅', '👄', '💋', '🩸',
		];
	}

	public static function paymentDateOfEachMonth($day)
	{
		$date = date('Y-m-' . $day . ' 13:00:00', strtotime('+1 month'));
		return $date;
	}

	public static function getDurationInMinutes($seconds)
	{
		if ($seconds >= 3600) {
			return gmdate('G:i:s', $seconds);
		} else {
			return gmdate('i:s', $seconds);
		}
	}

	public static function getResolutionVideo($width)
	{
		if ($width >= 1080 && $width < 3840) {
			return 'HD';
		} elseif ($width >= 3840) {
			return '4K';
		}
	}

	public static function checkCurrentDeviceSession($userIp, $userDevice, $userDeviceType, $userBrowser)
	{
		$agent = new Agent();
		// IP
		$ip = request()->ip();
		// Device
		$device  = $agent->device();
		// Device type
		$deviceType  = $agent->isPhone() ? 'phone' : 'desktop';
		// Browser
		$browser = $agent->browser();
		$browser = $browser . ' ' . $agent->version($browser);

		if ($userIp == $ip && $userDevice == $device && $userDeviceType == $deviceType && $userBrowser == $browser) {
			return true;
		}

		return false;
	}

	public static function createUsername($name, $id)
	{
		$name = explode(' ', $name);
		$firstName = str_slug($name[0]);
		$username = $firstName . $id;

		$findUsername = User::whereUsername($username)->first();

		if ($firstName && !$findUsername) {
			return $username;
		} else {
			$username = $firstName . $id . '-' . str_random(3);
			$findUsername = User::whereUsername($username)->first();

			if (!$findUsername) {
				return $username;
			}
		}

		return 'user' . $id;
	}

	public static function formatPrice($amount, $applyTax = null)
	{
		// Apply Taxes
		if (auth()->check() && $applyTax) {
			$isTaxable = auth()->user()->isTaxable();
			$taxes = 0;

			if ($applyTax && $isTaxable->count()) {
				foreach ($isTaxable as $tax) {
					$taxes += $tax->percentage;
				}

				$valueWithTax = number_format($taxes * $amount / 100, 2, '.', '');
				$amount = ($amount + $valueWithTax);
			}
		} // isTaxable

		switch (config('settings.wallet_format')) {
			case 'real_money':
				$moneyFormat = self::amountFormatDecimal($amount);
				break;

			case 'credits':
				$moneyFormat = $amount . ' ' . __('general.credits');
				break;

			case 'points':
				$moneyFormat = $amount . ' ' . __('general.points');
				break;

			case 'tokens':
				$moneyFormat = $amount . ' ' . __('general.tokens');
				break;
		}

		return $moneyFormat;
	}

	public static function priceWithoutFormat($amount)
	{
		switch (config('settings.wallet_format')) {
			case 'real_money':
				$moneyFormat = self::amountWithoutFormat($amount);
				break;

			case 'credits':
				$moneyFormat = $amount . ' ' . __('general.credits');
				break;

			case 'points':
				$moneyFormat = $amount . ' ' . __('general.points');
				break;

			case 'tokens':
				$moneyFormat = $amount . ' ' . __('general.tokens');
				break;
		}

		return $moneyFormat;
	}

	public static function symbolPositionLeft()
	{
		if (config('settings.wallet_format') == 'real_money') {
			return config('settings.currency_position') == 'left'  ? config('settings.currency_symbol') : ((config('settings.currency_position') == 'left_space') ? config('settings.currency_symbol') . ' ' : null);
		}

		return null;
	}

	public static function symbolPositionRight()
	{
		if (config('settings.wallet_format') == 'real_money') {
			return config('settings.currency_position') == 'right'  ? config('settings.currency_symbol') : ((config('settings.currency_position') == 'right_space') ? ' ' . config('settings.currency_symbol') : null);
		} else {
			switch (config('settings.wallet_format')) {
				case 'credits':
					return ' ' . __('general.credits');
					break;

				case 'points':
					return ' ' . __('general.points');
					break;

				case 'tokens':
					return ' ' . __('general.tokens');
					break;
			}
		}
	}

	public static function formatMonth()
	{
		$month = date('m');
		$daysMonth = now()->daysInMonth;
		$monthFormat = __("months.$month");

		// Days of Month
		for ($i = 1; $i <= $daysMonth; ++$i) {
			$monthsData[] =  "'$monthFormat $i'";
		}

		return implode(',', $monthsData);
	}

	public static function isCreatorLive($data, $id)
	{
		return isset($data) && in_array($id, $data) ? true : false;
	}

	public static function showLoginFormModal()
	{
		return request()->is('/') && config('settings.home_style') == 0
			|| request()->route()->named('profile')
			|| request()->is([
				'creators',
				'creators/*',
				'category/*',
				'p/*',
				'blog',
				'blog/post/*',
				'shop',
				'shop/product/*',
				'explore/creators/*'
			]) ? true : false;
	}

	public static function formatDateSchedule($date)
	{
		$date = strtotime($date);

		$day    = date('d', $date);
		$_month = date('m', $date);
		$month  = __("months.$_month");
		$year   = date('Y', $date);
		$time   = date('h:i A', $date);

		return $day . ' ' . $month . ', ' . $year . ' '. __('general.at') . ' ' . $time;
	}

	public static function isOnline(int $id): bool
	{
		return Cache::has('is-online-' . $id);
	}

	public static function amountGrossLivePrivateRequest($pricePerMinute, $minutes)
	{
		$amount = ($pricePerMinute * $minutes);

		// Aplly Taxes
		$isTaxable = auth()->user()->isTaxable();
		$taxes = 0;

		if ($isTaxable->count()) {
			foreach ($isTaxable as $tax) {
				$taxes += $tax->percentage;
			}

			$amount = $amount + ($taxes * $amount / 100);

			if (config('settings.currency_code') == 'JPY') {
				$amount = round($amount);
			} else {
				$amount = number_format($amount, 2, '.', '');
			}

			return $amount;
		} // isTaxable

		return $amount;
	}
}
