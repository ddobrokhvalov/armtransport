<?
class upload extends lib_abstract {

	public static function upload_file( $file_descr, $upload_path, $create_upload_dir = true, $rewrite_file = true, $size = false)
	{
		$tmp_name = $file_descr['tmp_name']; $name = $file_descr['name'];
		
		$name = self::get_translit_file_name( $name );
		
		if ( !self::is_valid_name( $name ) )
			throw new Exception( "Неверное имя файла" );
		
		if ( !self::is_valid_ext( $name ) )
			throw new Exception( "Неверное расширение" );
		
		if( $create_upload_dir && !file_exists( $upload_path ) )
			if ( !( @mkdir( $upload_path , 0777, true) ) )
				throw new Exception( "Невозможно создать папку" );
		
		if( !$rewrite_file )
			$name = self::get_unique_file_name( $upload_path, $name );
		
		if ( !( @move_uploaded_file( $tmp_name, $upload_path.'/'.$name ) &&
				@chmod( $upload_path.'/'.$name, 0777 ) ) )
			throw new Exception( "Невозможно загрузить файл" );
		
		$pathFile = realpath( $upload_path.'/'.$name );
		
		if($size){
			$real_size = getimagesize( $pathFile );
			$realW = $real_size[0];
			$realH = $real_size[1];
			$ratio = $realW/$realH;
				if($size['w'] / $size['h'] > $ratio) {
					$tempH = round($size['w'] / $ratio);
					$tempW = $size['w'];
				} elseif ($size['w'] / $size['h'] < $ratio) {
					$tempW = round($size['h'] * $ratio);
					$tempH = $size['h'];
				} else {
					$tempW = $size['w'];
					$tempH = $size['h'];
				}
			self::resize($pathFile, $tempW, $tempH, 100, $pathFile);
		}
		
		return $pathFile;
	}
	
	public static function is_valid_name( $name )
	{
		return !preg_match( '/[^a-z0-9_\.\[\]-]/i', $name );
	}
	
	public static function is_valid_ext( $name )
	{
		$name_parts = pathinfo( $name );
		$ext = $name_parts['extension'];
		
		return !in_array( $ext, self::$forbidden_extensions );
	}
	
	public static function get_unique_file_name( $path, $name )
	{
		$point_index = strrpos( $name, '.' );
		$base = ( $point_index !== false ) ? substr( $name, 0, $point_index ) : $name;
		$ext = ( $point_index !== false ) ? substr( $name, $point_index, strlen( $name ) ) : '';
		
		$new_name = $name; $n = 0;
		while ( file_exists( $path . '/' . $new_name ) )
			$new_name = $base . '[' . ( ++$n ) . ']' . $ext;
		return $new_name;
	}
	
	public static function get_translit_file_name( $name )
	{
		return preg_replace( '/[^a-z0-9_\.\[\]-]/i', '', strtr( $name, self::$translit ) );
	}
	
	public static function resize( $img, $w='null', $h='null', $pin, $pathResult ) {
		
		if($img)
		{
			$imginfo = getimagesize($img);
			$srcimg = null;
			switch($imginfo[2])
			{
				case 1:
					$srcimg = imagecreatefromgif($img);
					break;
				case 2:
					$srcimg = imagecreatefromjpeg($img);
					break;
				case 3:
					$srcimg = imagecreatefrompng($img);
					break;
				case 6:
					$srcimg = self::imagecreatefrombmp($img);
					break;
				default:
					return false;
			}

			if($srcimg && ($w || $h))
			{
				$xoffset = 0;
				$yoffset = 0;
				$iwidth = $imginfo[0];
				$iheight = $imginfo[1];
				if($w && !$h)
					$h = round($w*$imginfo[1]/$imginfo[0]);
				elseif($h && !$w)
					$w = round($h*$imginfo[0]/$imginfo[1]);
				elseif(!$h && !$w)
				{
					$w = $imginfo[0];
					$h = $imginfo[1];
				}
				else
				{
					if($iheight)$k0 = $iwidth/$iheight;
					if($h)$k1 = $w/$h;
					if($k1 < $k0)
					{
						$iwidth  = round($k1*$iheight);
						$xoffset = round(abs($imginfo[0] - $iwidth)/2);
					}
					else
					{
						$iheight = round($iwidth/$k1);
						$yoffset = round(abs($imginfo[1] - $iheight)/2);
					}
				}
				$dstimg = imagecreatetruecolor($w, $h);
				imagecopyresampled($dstimg, $srcimg, 0, 0, $xoffset, $yoffset, $w, $h, $iwidth, $iheight);
				//$dstimg = self::UnsharpMask($dstimg);
    			//сохраняем файл во временный файл, а потом атомарно его помещаем по требуемому пути
				//нужно что-бы избежать возможных глюков от одновременой записи из разных запросов
				$pathResultTmp = $pathResult . uniqid();
				imagejpeg($dstimg, $pathResultTmp, $pin);
				imagedestroy($dstimg);
				rename($pathResultTmp, $pathResult);
			}
		}
		return $dstimg;
	}
	
	public static $forbidden_extensions = array( 'htaccess' );
	
	public static $translit = array(
		' ' => '_', 'Ё' => 'YO', 'ё' => 'yo', 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
		'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I', 'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
		'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'KH', 'Ц' => 'TS', 'Ч' => 'CH',
		'Ш' => 'SH', 'Щ' => 'SHCH', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'U', 'Я' => 'YA', 'а' => 'a',
		'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y',
		'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
		'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ъ' => '', 'ы' => 'y',
		'ь' => '', 'э' => 'e', 'ю' => 'u', 'я' => 'ya', '№' => 'N' );
}