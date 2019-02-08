<?

$str = "Hello";

$bgcolor = 'ffffff';
$fgcolor = 'ffffff';

if(isset($HTTP_GET_VARS['string']))
	$str = $HTTP_GET_VARS['string'];

if(isset($HTTP_GET_VARS['bgc']))
	$bgcolor = $HTTP_GET_VARS['bgc'];

if(isset($HTTP_GET_VARS['fgc']))
	$fgcolor = $HTTP_GET_VARS['fgc'];

class glyph
{
	var $data = NULL;
	var $width = 0;
	var $height = 0;
	var $pos = 0;
	var $size = 0;
	var $offset = 0;

	function getb()
	{
		return $this->data[$this->pos++];
	}

	function addbyte($zeuch)
	{
		$this->data[$this->size] = chr($zeuch);
		++$this->size;
	}

	function calcoffset($gesh)
	{
		$this->offset = $gesh - $this->height;
	}
}

class vfile
{
	var $data = '';
	var $pos = 0;
	var $size = 0;

	function vgetc()
	{
		return $this->data[$this->pos++];
	}

	function vseek($offset)
	{
		$this->pos += $offset;
	}

	function vrewind()
	{
		$this->pos = 0;
	}
}

class data
{
	var $size = 0;
	var $data = NULL;

	function addbyte($zeuch)
	{
		$this->data[$this->size] = chr($zeuch);
		++$this->size;
	}

	function adddword($str)
	{
		$this->addbyte( hexdec($str[0] . $str[1]) );
		$this->addbyte( hexdec($str[2] . $str[3]) );
		$this->addbyte( hexdec($str[4] . $str[5]) );
		$this->addbyte( hexdec($str[6] . $str[7]) );
	}
}

function dec2lehex($zahl)
{
	$temp = sprintf("%08s", dechex($zahl));

	$temp2 = $temp[6] . $temp[7] . $temp[4] . $temp[5];
	$temp2 .= $temp[2] . $temp[3] . $temp[0] . $temp[1];

	return $temp2;
}

function addcolor(&$wohin, $was)
{
	$wohin->addbyte( hexdec( $was[4] . $was[5] ) );
	$wohin->addbyte( hexdec( $was[2] . $was[3] ) );
	$wohin->addbyte( hexdec( $was[0] . $was[1] ) );
}

$staben = NULL;
$width = 0;
$height = 0;

$i = 0;
$count = 32;

$vfile = new vfile;

$vfile->size = filesize("uo.fnt");

$file = fopen("uo.fnt", "rb");

$vfile->data = fread($file, $vfile->size);

fclose($file);


while($str[$i])
{
    $vfile->vrewind();
    $count = 32;

	$number = ord($str[$i]);

	while($number > $count)
	{
		$temp1 = ord($vfile->vgetc());
		$temp2 = ord($vfile->vgetc());

		$vfile->vseek($temp1 * $temp2);

		++$count;
	}

	if($number == $count)
	{
		$awidth = ord($vfile->vgetc());
		$aheight = ord($vfile->vgetc());

		if($aheight > $height)
			$height = $aheight;

		$width += $awidth;

		$staben[$i] = new glyph;

		$staben[$i]->width = $awidth;
		$staben[$i]->height = $aheight;

		for($k = 0; $k < ($awidth * $aheight); ++$k)
		{
			$pxl = $vfile->vgetc();

			if($pxl == chr(0x00))
			{
				addcolor($staben[$i], $bgcolor);;
			}
			else if($pxl == chr(0x01))
			{
				addcolor($staben[$i], '000000');
			}
			else if($pxl == chr(0x02))
			{
				addcolor($staben[$i], $fgcolor);
			}
		}
	}

	++$i;
}

$count = $i;

for($i = 0; $i < $count; ++$i)
{
	$staben[$i]->calcoffset($height);
}

$temps = $width*3;

while( ( $temps % 4 ) > 0 )
	++$temps;

$xtrapz = $temps - $width*3;

$artsize = dec2lehex( $temps*3 + $height*$xtrapz );
$gessize = dec2lehex( $temps*3 + $height*$xtrapz + 54 );
$wdword = dec2lehex( $width );
$hdword = dec2lehex( $height );

$data = new data;

$data->addbyte( 0x42 );
$data->addbyte( 0x4D );  // 'BM' = Windows Bitmap Identifier

$data->adddword( $gessize );

$data->adddword( '00000000' );  // reserviert

$data->adddword( '36000000' );  // offset zu pixeldata

$data->adddword( '28000000' );  // größe der header bitmapinfo

$data->adddword( $wdword );  // breite
$data->adddword( $hdword );  // höhe

$data->adddword( '01001800' );  // ein 'plane' (?), 24 bpp

$data->adddword( '00000000' );  // keine komprimierung

$data->adddword( $artsize );  // größe der pixeldaten

$data->adddword( '00000000' );  // hres
$data->adddword( '00000000' );  // vres

$data->adddword( '00000000' );  // colors - 0 = 24bit
$data->adddword( '00000000' );  // important colors - 0 = alle

$zeilen = new data;

for($y = 0; $y < $height; ++$y)
{
	for($i = 0; $i < $count; ++$i)
	{
		for($x = 0; $x < $staben[$i]->width; ++$x)
		{
			if($staben[$i]->offset <= $y)
			{
				$zeilen->addbyte( ord($staben[$i]->getb()) );
				$zeilen->addbyte( ord($staben[$i]->getb()) );
				$zeilen->addbyte( ord($staben[$i]->getb()) );
			}
			else
			{
				addcolor($zeilen, $bgcolor);
			}
		}
	}

	for($i = 0; $i < $xtrapz; ++$i)
		$zeilen->addbyte( 0x00 );
}

header ("Content-type: image/bmp");

for($i = 0; $i < $data->size; ++$i)
	echo $data->data[$i];

$bwidth = $width*3 + $xtrapz;

for($i = $height-1; $i >= 0; --$i)
{
	for($j = 0; $j < $bwidth; ++$j)
		echo $zeilen->data[$i*$bwidth + $j];
}

?>
