<?php
function app_name()
{
    return 'Realisasi Investasi';
}

function getKota($nama)
{
    $nama = trim(str_replace('Kab.','Kabupaten',$nama));
    $get = App\Models\kota::where('nama', $nama)->first();
    if ($get) {
        return $get->id;
    }
}

function bersih( $str, $delimiter = '-', $options = array() )
{

    $str = mb_convert_encoding( ( string ) $str, 'UTF-8', mb_list_encodings() );

    $defaults = array(
        'delimiter' => $delimiter,
        'limit' => null,
        'lowercase' => true,
        'replacements' => array(),
        'transliterate' => false,
    );

    $options = array_merge( $defaults, $options );

    $char_map = array(
        // Latin
        'ÃƒÂ€' => 'A', 'ÃƒÂ' => 'A', 'ÃƒÂ‚' => 'A', 'ÃƒÂƒ' => 'A', 'ÃƒÂ„' => 'A', 'ÃƒÂ…' => 'A', 'ÃƒÂ†' => 'AE', 'ÃƒÂ‡' => 'C',
        'ÃƒÂˆ' => 'E', 'ÃƒÂ‰' => 'E', 'ÃƒÂŠ' => 'E', 'ÃƒÂ‹' => 'E', 'ÃƒÂŒ' => 'I', 'ÃƒÂ' => 'I', 'ÃƒÂŽ' => 'I', 'ÃƒÂ' => 'I',
        'ÃƒÂ' => 'D', 'ÃƒÂ‘' => 'N', 'ÃƒÂ’' => 'O', 'ÃƒÂ“' => 'O', 'ÃƒÂ”' => 'O', 'ÃƒÂ•' => 'O', 'ÃƒÂ–' => 'O', 'Ã…Â' => 'O',
        'ÃƒÂ˜' => 'O', 'ÃƒÂ™' => 'U', 'ÃƒÂš' => 'U', 'ÃƒÂ›' => 'U', 'ÃƒÂœ' => 'U', 'Ã…Â°' => 'U', 'ÃƒÂ' => 'Y', 'ÃƒÂž' => 'TH',
        'ÃƒÂŸ' => 'ss',
        'Ãƒ ' => 'a', 'ÃƒÂ¡' => 'a', 'ÃƒÂ¢' => 'a', 'ÃƒÂ£' => 'a', 'ÃƒÂ¤' => 'a', 'ÃƒÂ¥' => 'a', 'ÃƒÂ¦' => 'ae', 'ÃƒÂ§' => 'c',
        'ÃƒÂ¨' => 'e', 'ÃƒÂ©' => 'e', 'ÃƒÂª' => 'e', 'ÃƒÂ«' => 'e', 'ÃƒÂ¬' => 'i', 'ÃƒÂ­' => 'i', 'ÃƒÂ®' => 'i', 'ÃƒÂ¯' => 'i',
        'ÃƒÂ°' => 'd', 'ÃƒÂ±' => 'n', 'ÃƒÂ²' => 'o', 'ÃƒÂ³' => 'o', 'ÃƒÂ´' => 'o', 'ÃƒÂµ' => 'o', 'ÃƒÂ¶' => 'o', 'Ã…Â‘' => 'o',
        'ÃƒÂ¸' => 'o', 'ÃƒÂ¹' => 'u', 'ÃƒÂº' => 'u', 'ÃƒÂ»' => 'u', 'ÃƒÂ¼' => 'u', 'Ã…Â±' => 'u', 'ÃƒÂ½' => 'y', 'ÃƒÂ¾' => 'th',
        'ÃƒÂ¿' => 'y',

        // Latin symbols
        'Ã‚Â©' => '(c)',

        // Greek
        'ÃŽÂ‘' => 'A', 'ÃŽÂ’' => 'B', 'ÃŽÂ“' => 'G', 'ÃŽÂ”' => 'D', 'ÃŽÂ•' => 'E', 'ÃŽÂ–' => 'Z', 'ÃŽÂ—' => 'H', 'ÃŽÂ˜' => '8',
        'ÃŽÂ™' => 'I', 'ÃŽÂš' => 'K', 'ÃŽÂ›' => 'L', 'ÃŽÂœ' => 'M', 'ÃŽÂ' => 'N', 'ÃŽÂž' => '3', 'ÃŽÂŸ' => 'O', 'ÃŽ ' => 'P',
        'ÃŽÂ¡' => 'R', 'ÃŽÂ£' => 'S', 'ÃŽÂ¤' => 'T', 'ÃŽÂ¥' => 'Y', 'ÃŽÂ¦' => 'F', 'ÃŽÂ§' => 'X', 'ÃŽÂ¨' => 'PS', 'ÃŽÂ©' => 'W',
        'ÃŽÂ†' => 'A', 'ÃŽÂˆ' => 'E', 'ÃŽÂŠ' => 'I', 'ÃŽÂŒ' => 'O', 'ÃŽÂŽ' => 'Y', 'ÃŽÂ‰' => 'H', 'ÃŽÂ' => 'W', 'ÃŽÂª' => 'I',
        'ÃŽÂ«' => 'Y',
        'ÃŽÂ±' => 'a', 'ÃŽÂ²' => 'b', 'ÃŽÂ³' => 'g', 'ÃŽÂ´' => 'd', 'ÃŽÂµ' => 'e', 'ÃŽÂ¶' => 'z', 'ÃŽÂ·' => 'h', 'ÃŽÂ¸' => '8',
        'ÃŽÂ¹' => 'i', 'ÃŽÂº' => 'k', 'ÃŽÂ»' => 'l', 'ÃŽÂ¼' => 'm', 'ÃŽÂ½' => 'n', 'ÃŽÂ¾' => '3', 'ÃŽÂ¿' => 'o', 'ÃÂ€' => 'p',
        'ÃÂ' => 'r', 'ÃÂƒ' => 's', 'ÃÂ„' => 't', 'ÃÂ…' => 'y', 'ÃÂ†' => 'f', 'ÃÂ‡' => 'x', 'ÃÂˆ' => 'ps', 'ÃÂ‰' => 'w',
        'ÃŽÂ¬' => 'a', 'ÃŽÂ­' => 'e', 'ÃŽÂ¯' => 'i', 'ÃÂŒ' => 'o', 'ÃÂ' => 'y', 'ÃŽÂ®' => 'h', 'ÃÂŽ' => 'w', 'ÃÂ‚' => 's',
        'ÃÂŠ' => 'i', 'ÃŽÂ°' => 'y', 'ÃÂ‹' => 'y', 'ÃŽÂ' => 'i',

        // Turkish
        'Ã…Âž' => 'S', 'Ã„Â°' => 'I', 'ÃƒÂ‡' => 'C', 'ÃƒÂœ' => 'U', 'ÃƒÂ–' => 'O', 'Ã„Âž' => 'G',
        'Ã…ÂŸ' => 's', 'Ã„Â±' => 'i', 'ÃƒÂ§' => 'c', 'ÃƒÂ¼' => 'u', 'ÃƒÂ¶' => 'o', 'Ã„ÂŸ' => 'g',

        // Russian
        'ÃÂ' => 'A', 'ÃÂ‘' => 'B', 'ÃÂ’' => 'V', 'ÃÂ“' => 'G', 'ÃÂ”' => 'D', 'ÃÂ•' => 'E', 'ÃÂ' => 'Yo', 'ÃÂ–' => 'Zh',
        'ÃÂ—' => 'Z', 'ÃÂ˜' => 'I', 'ÃÂ™' => 'J', 'ÃÂš' => 'K', 'ÃÂ›' => 'L', 'ÃÂœ' => 'M', 'ÃÂ' => 'N', 'ÃÂž' => 'O',
        'ÃÂŸ' => 'P', 'Ã ' => 'R', 'ÃÂ¡' => 'S', 'ÃÂ¢' => 'T', 'ÃÂ£' => 'U', 'ÃÂ¤' => 'F', 'ÃÂ¥' => 'H', 'ÃÂ¦' => 'C',
        'ÃÂ§' => 'Ch', 'ÃÂ¨' => 'Sh', 'ÃÂ©' => 'Sh', 'ÃÂª' => '', 'ÃÂ«' => 'Y', 'ÃÂ¬' => '', 'ÃÂ­' => 'E', 'ÃÂ®' => 'Yu',
        'ÃÂ¯' => 'Ya',
        'ÃÂ°' => 'a', 'ÃÂ±' => 'b', 'ÃÂ²' => 'v', 'ÃÂ³' => 'g', 'ÃÂ´' => 'd', 'ÃÂµ' => 'e', 'Ã‘Â‘' => 'yo', 'ÃÂ¶' => 'zh',
        'ÃÂ·' => 'z', 'ÃÂ¸' => 'i', 'ÃÂ¹' => 'j', 'ÃÂº' => 'k', 'ÃÂ»' => 'l', 'ÃÂ¼' => 'm', 'ÃÂ½' => 'n', 'ÃÂ¾' => 'o',
        'ÃÂ¿' => 'p', 'Ã‘Â€' => 'r', 'Ã‘Â' => 's', 'Ã‘Â‚' => 't', 'Ã‘Âƒ' => 'u', 'Ã‘Â„' => 'f', 'Ã‘Â…' => 'h', 'Ã‘Â†' => 'c',
        'Ã‘Â‡' => 'ch', 'Ã‘Âˆ' => 'sh', 'Ã‘Â‰' => 'sh', 'Ã‘ÂŠ' => '', 'Ã‘Â‹' => 'y', 'Ã‘ÂŒ' => '', 'Ã‘Â' => 'e', 'Ã‘ÂŽ' => 'yu',
        'Ã‘Â' => 'ya',

        // Ukrainian
        'ÃÂ„' => 'Ye', 'ÃÂ†' => 'I', 'ÃÂ‡' => 'Yi', 'Ã’Â' => 'G',
        'Ã‘Â”' => 'ye', 'Ã‘Â–' => 'i', 'Ã‘Â—' => 'yi', 'Ã’Â‘' => 'g',

        // Czech
        'Ã„ÂŒ' => 'C', 'Ã„ÂŽ' => 'D', 'Ã„Âš' => 'E', 'Ã…Â‡' => 'N', 'Ã…Â˜' => 'R', 'Ã… ' => 'S', 'Ã…Â¤' => 'T', 'Ã…Â®' => 'U',
        'Ã…Â½' => 'Z',
        'Ã„Â' => 'c', 'Ã„Â' => 'd', 'Ã„Â›' => 'e', 'Ã…Âˆ' => 'n', 'Ã…Â™' => 'r', 'Ã…Â¡' => 's', 'Ã…Â¥' => 't', 'Ã…Â¯' => 'u',
        'Ã…Â¾' => 'z',

        // Polish
        'Ã„Â„' => 'A', 'Ã„Â†' => 'C', 'Ã„Â˜' => 'e', 'Ã…Â' => 'L', 'Ã…Âƒ' => 'N', 'ÃƒÂ“' => 'o', 'Ã…Âš' => 'S', 'Ã…Â¹' => 'Z',
        'Ã…Â»' => 'Z',
        'Ã„Â…' => 'a', 'Ã„Â‡' => 'c', 'Ã„Â™' => 'e', 'Ã…Â‚' => 'l', 'Ã…Â„' => 'n', 'ÃƒÂ³' => 'o', 'Ã…Â›' => 's', 'Ã…Âº' => 'z',
        'Ã…Â¼' => 'z',

        // Latvian
        'Ã„Â€' => 'A', 'Ã„ÂŒ' => 'C', 'Ã„Â’' => 'E', 'Ã„Â¢' => 'G', 'Ã„Âª' => 'i', 'Ã„Â¶' => 'k', 'Ã„Â»' => 'L', 'Ã…Â…' => 'N',
        'Ã… ' => 'S', 'Ã…Âª' => 'u', 'Ã…Â½' => 'Z',
        'Ã„Â' => 'a', 'Ã„Â' => 'c', 'Ã„Â“' => 'e', 'Ã„Â£' => 'g', 'Ã„Â«' => 'i', 'Ã„Â·' => 'k', 'Ã„Â¼' => 'l', 'Ã…Â†' => 'n',
        'Ã…Â¡' => 's', 'Ã…Â«' => 'u', 'Ã…Â¾' => 'z'
    );

    $str = preg_replace( array_keys( $options['replacements'] ), $options['replacements'], $str );

    if ( $options['transliterate'] ) {
        $str = str_replace( array_keys( $char_map ), $char_map, $str );
    }

    $str = preg_replace( '/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
    $str = preg_replace( '/(' . preg_quote( $options['delimiter'], '/') . '){2,}/', '$1', $str);
    $str = substr( $str, 0, ( $options['limit'] ? $options['limit'] : strlen( $str ) ) );
    $str = trim( $str, $options['delimiter'] );

    return $options['lowercase'] ? strtolower( $str ) : $str;

}

function meta_description($string) {
    if(strlen($string) > 160)
    {
        $truncate = 160;
        $string = preg_replace ('/<[^>]*>/', ' ', $string);
        $string = str_replace("\r", '', $string);
        $string = str_replace("\n", ' ', $string);
        $string = str_replace("\t", ' ', $string);
        $string = str_replace("&nbsp;&nbsp;&nbsp;", ' ', $string);
        $string = str_replace("&nbsp;&nbsp;", ' ', $string);
        $string = str_replace("&nbsp; &nbsp;", ' ', $string);
        $string = trim(preg_replace('/ {2,}/', ' ', $string));
        $string = preg_replace("/&#?[a-z0-9]{2,8};/i","",$string);
        $string = preg_replace("#[[:punct:]]#", "", $string);
        $string = trim($string, ", ");
        $string = trim($string, " ,");
        $string = trim($string, ",");
        $string = str_replace("  ", ' ', $string);
        $string = trim($string);
        $string = substr($string, 0, $truncate).'...';
    }
    return $string;
}

function meta_keywords($string) {
    $string = preg_replace ('/<[^>]*>/', ' ', $string);
    $string = str_replace("\r", '', $string);
    $string = str_replace("\n", ' ', $string);
    $string = str_replace("\t", ' ', $string);
    $string = trim(preg_replace('/ {2,}/', ' ', $string));
    $string = preg_replace("/&#?[a-z0-9]{2,8};/i","",$string);
    $string = preg_replace("#[[:punct:]]#", "", $string);
    $string = preg_replace("[^-\w\d\s\.=$'€%]",'',$string);
    $string = str_replace(' ', ', ', $string);
    $string = str_replace(' ,', '', $string);
    $string = trim($string, ", ");
    $string = trim($string, " ,");
    $string = trim($string, ",");
    $string = trim($string);
    return $string;
}


function formatRupiah($angka){
    $rupiah = "Rp " . number_format($angka, 0, ',', '.');
    return $rupiah;
}

function formatUSD($angka) {
    // Format USD dengan dua angka desimal dan pemisah ribuan
    $usd = "$" . number_format($angka, 0, '.', ',');
    return $usd;
}


function roleMiddle($kode)
{
    switch($kode)
    {
        case 'super_admin':
            $id = 1;
            break;
        case 'auth_destinasi':
            $id = 2;
            break;
        case 'auth_ekraf':
            $id = 3;
            break;
        case 'auth_sdp':
            $id = 4;
            break;
        case 'auth_pemasaran':
            $id = 5;
            break;
        case 'auth_upt':
            $id = 6;
            break;
        case 'auth_kabupaten':
            $id = 7;
            break;
        default:
            $id =0;
            break;
    }
    return $id;
}

function status($kode)
{
    switch ($kode) 
    {
        case '1':
            $status = '<span class="badge badge-info">Diusulkan</span>';
            break;
        case '2':
            $status = '<span class="badge badge-warning">Proses</span>';
            break;
        case '3':
            $status = '<span class="badge badge-success">Selesai</span>';
            break;
        case '4':
            $status = '<span class="badge badge-danger">Ditolak</span>';
            break;
        default:
            $status ="-";
            break;
    }
    return $status;
}

function formatAngkat($number)
{
    return number_format((float)$number, 2, '.', '');
}

function formatAngkaJenis($number)
{
    return number_format($number, 0, '.', '.');
}

function formatAngka($number)
{
    return ($number == 0) ? '-' : number_format($number, 0, '.', '.');
}

function formatAngkaCurrency($number)
{
    return ($number == 0) ? '-' : number_format($number, 2, ',', '.');
}

function tgl_indo($tanggal){
    $tanggal = date('Y-m-d',strtotime($tanggal));

	$bulan = array (
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode('-', $tanggal);
	return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}


function getBulan($id){

	$bulan = array (
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	return $bulan[$id];
}

function formatNominal($nominal) {
    if ($nominal == 0) {
        return "Rp 0";
    }
    
     // Daftar pembilang untuk setiap satuan
     $satuanPembilang = ["", "K", "M", "B", "T"];

    // Hitung jumlah digit dalam nominal
    $digitCount = floor(log10($nominal)) + 1;

    // Tentukan pembilang yang sesuai
    $pembilangIndex = floor(($digitCount - 1) / 3);

    // Hitung nilai dengan pembilang
    $nilaiFormatted = $nominal / pow(10, $pembilangIndex * 3);

    // Ambil satu digit desimal tanpa pembulatan
    $nilaiFormatted = floor($nilaiFormatted * 10) / 10;

    // Gabungkan nilai dan pembilang
    $hasilFormatted = "Rp " . $nilaiFormatted . " " . $satuanPembilang[$pembilangIndex];

    return $hasilFormatted;
}

function getKurs($tw, $tahun)
{
    $cek = DB::table('pmas')->where('jenis_berjangka_id', $tw)->where('tahun', $tahun)->value('kurs');

    // Check if $cek has a value
    return $cek !== null ? $cek : 0;
}