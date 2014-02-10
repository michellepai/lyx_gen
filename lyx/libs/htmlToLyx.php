<?php
function strallpos($haystack, $needle, $offset = 0) {
    $result = array();
    for ($i = $offset; $i < strlen($haystack); $i++) {
        $pos = strpos($haystack, $needle, $i);
        if ($pos !== FALSE) {
            $offset = $pos;
            if ($offset >= $i) {
                $i = $offset;
                $result[] = $offset;
            }
        }
    }
    return $result;
}
//$html = '<ul><li>bullist</li><li>bullist</li></ul><p>paragraph paragraph paragraph</p><ol><li>numlist</li><li>numlist</li><li>numlist</li></ol><p><em>italic&nbsp;</em>paragraph&nbsp;<em>italic&nbsp;</em>paragraph&nbsp;<strong>bold boldbold <em>boldtalic boltalic</em></strong></p>';

function htmlToLyx($out){
    $html_tag = array('<p>', '</p>', '<em>', '</em>', '<strong>', '</strong>', '&nbsp;');
$lyx_tag = array("\r\n\\begin_layout Plain Layout\r\n", "\r\n\\end_layout\r\n", "\r\n\\shape italic\r\n", "\r\n\\shape default\r\n" , "\r\n\\series bold\r\n", "\r\n\\series default\r\n", "\x20");
$html_bullist = array('<li>', '</li>');
$lyx_bullist = array("\r\n\\begin_layout Itemize\r\n", "\r\n\\end_layout\r\n");
$html_numlist = array('<li>', '</li>');
$lyx_numlist = array("\r\n\\begin_layout Enumerate\r\n", "\r\n\\end_layout\r\n");
//str_replace($html_tag, $lyx_tag, $desc);
$startsAt = strallpos($out, "<ul>");
$endsAt = strallpos($out, "</ul>");
for ($i = 0; $i < sizeof($startsAt); $i++) {
    $beforeReplace[$i] = substr($out, $startsAt[$i] + 4, $endsAt[$i] - $startsAt[$i]);
    $afterReplace[$i] = str_replace($html_bullist, $lyx_bullist, $beforeReplace[$i]);
}
$after = str_replace($beforeReplace, $afterReplace, $out);

//for numlist
$startsAt = strallpos($out, "<ol>");
$endsAt = strallpos($out, "</ol>");
for ($i = 0; $i < sizeof($startsAt); $i++) {
    $beforeNum[$i] = substr($out, $startsAt[$i] + 4, $endsAt[$i] - $startsAt[$i]);
    $afterNum[$i] = str_replace($html_numlist, $lyx_numlist, $beforeNum[$i]);
}
$after = str_replace($beforeNum, $afterNum, $after);
$after = strip_tags(str_replace($html_tag, $lyx_tag, $after));
return $after;
    
}
//echo htmlToLyx($html);

?>
