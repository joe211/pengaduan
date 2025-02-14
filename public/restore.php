<?php
include 'dom.php';
$connect = mysqli_connect('localhost','root','','bkd_backup');
$connect2 = mysqli_connect('localhost','root','','project_bkd');
$getPost = mysqli_query($connect,"select * from wp_posts where post_type='post' and post_status = 'publish'");
while($r=mysqli_fetch_array($getPost))
{
    $kat =  getCat($connect,$connect2,$r['ID']);
    $thumb =  getThumb($r['post_name']);
    
    $content = $r['post_name'];
    $dom = new \DomDocument();
    libxml_use_internal_errors(true);
    $dom->loadHtml($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $images = $dom->getElementsByTagName('img');
    foreach($images as $img)
    {
        $src = $img->getAttribute('src');
        if(preg_match('/data:image/', $src))
        {
            preg_match('/data:image\/(?<mime>.*?)\;/', $src, $groups);
            $mimetype = $groups['mime'];
            $filename = $nama_bersih.'_'.rand();
            $filepath = 'uploads/images/gambar-berita/'.$filename.'.'.$mimetype;
            $nama = $filename.'.'.$mimetype;
            file_put_contents($filepath,file_get_contents($src));
            $truepath = 'https://bkd.riau.go.id/public/uploads/images/gambar-berita/'.$filename.'.'.$mimetype;
            $new_src =$truepath;
            $img->removeAttribute('src');
            $img->setAttribute('src', $new_src);
        }
    }

    // echo "INSERT INTO `beritas`( `kategori_berita_id`, `user_id`, `judul`, `konten`, `thumbnail`, `meta_deskripsi`, `meta_keyword`, `slug`, `tajuk`, `dilihat`, `created_at`, `updated_at`) VALUES ('".$kat."','".$r['post_author']."','".$r['post_title']."','".$r['post_content']."','".$thumb."','".meta_description($r['post_content'])."','".meta_keywords($r['post_title'])."','".$r['post_name']."','0','0','".$r['post_date']."','".$r['post_modified']."')";
    $save = mysqli_query($connect2,"INSERT INTO `beritas`( `kategori_berita_id`, `user_id`, `judul`, `konten`, `thumbnail`, `meta_deskripsi`, `meta_keyword`, `slug`, `tajuk`, `dilihat`, `created_at`, `updated_at`) VALUES ('".$kat."','2','".$r['post_title']."','".$r['post_content']."','".$thumb."','".meta_description($r['post_content'])."','".meta_keywords($r['post_title'])."','".$r['post_name']."','0','0','".$r['post_date']."','".$r['post_modified']."')");
}

function getCat($connect,$connect2,$id)
{
    $cat = mysqli_query($connect,"select wp_terms.name,wp_terms.slug from wp_term_relationships left join wp_term_taxonomy on wp_term_taxonomy.term_taxonomy_id=wp_term_relationships.term_taxonomy_id left join wp_terms on wp_terms.term_id=wp_term_taxonomy.term_id where wp_term_taxonomy.taxonomy='category' AND wp_term_relationships.object_id='".$id."'");
    $r = mysqli_fetch_array($cat);
    if(!isset($r['name']))
    {
        return 2;
    }
    $nama =  $r['name'];
    $slug =  $r['slug'];
    $time = date('Y-m-d H:i:s');
    $cek = mysqli_query($connect2,"select * from kategori_beritas where nama='".$nama."'");
    if(mysqli_num_rows($cek)== 0)
    {
        
        $save = mysqli_query($connect2,"INSERT INTO `kategori_beritas`(`nama`, `slug`, `created_at`, `updated_at`) VALUES ('".$nama."','".$slug."','".$time."','".$time."') ");
        $id = mysqli_insert_id($connect2);
    }
    else
    {
       
        $r = mysqli_fetch_array($cek);
        $id = $r['id'];
    }
    return $id;
}

function getThumb($url)
{
    $data = file_get_contents("https://bkd.riau.go.id/".$url);
    $dom = new simple_html_dom();
    $dom = $dom->load($data);   
    foreach($dom->find('.post-media img') as $key)
    {
        if(isset($key->src))
        {
            $file_name =  "thumbnail-".basename($key->src);
            $save = file_put_contents('uploads/images/gambar-berita/'.$file_name,file_get_contents($key->src));
        }
    }
    return isset($file_name) ? $file_name : 'default.jpg';
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