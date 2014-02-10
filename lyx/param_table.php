<?php

header("Content-type: text/plain");
$io;
$obj = array();

if ($_POST['section'] == 'input' . $i) {
    header("Content-Disposition: attachment; filename=op" . $i . "_input.lyx");
    $io = 6;
}
if ($_POST['section'] == 'output' . $i) {
    header("Content-Disposition: attachment; filename=op" . $i . "_output.lyx");
    $io = 7;
}

include 'libs/htmlToLyx.php';
$objCount=0;
do {
    ++$objCount;
    if ($objCount === 1) {
        $obj[$objCount]['name'] = 'Parameter';
    } else {
        $obj[$objCount]['name'] = 'Private Object: '.$_POST["ops" . $i . "/" . $io . "/" . $objCount . "/1_name"];
    }
    $pCount = 1;
    do {
        $desc =  $_POST["ops" . $i . "/" . $io . "/" . $objCount . "/" . $pCount . "_desc"];
        $desc = htmlToLyx($desc);
        $obj[$objCount]['param'][$pCount] = array(
            'name' => $_POST["ops" . $i . "/" . $io . "/" . $objCount . "/" . $pCount . "_param"],
            'type' => $_POST["ops" . $i . "/" . $io . "/" . $objCount . "/" . $pCount . "_type"],
            'require' => $_POST["ops" . $i . "/" . $io . "/" . $objCount . "/" . $pCount . "_required"],
            'description' => $desc,
            'location' => $_POST["ops" . $i . "/" . $io . "/" . $objCount . "/" . $pCount . "_location"]);
        $pCount++;
    } while (isset($_POST["ops" . $i . "/" . $io . "/" . $objCount . "/" . $pCount . "_param"]));
    $obj[$objCount]['pNum']=$pCount;
} while (isset($_POST["ops" . $i . "/" . $io . "/" . ($objCount+1) . "/1_name"]));

echo <<<'HEAD'
#LyX 2.0 created this file. For more info see http://www.lyx.org/
\lyxformat 413
\begin_document
\begin_header
\textclass article
\use_default_options true
\maintain_unincluded_children false
\language english
\language_package default
\inputencoding auto
\fontencoding global
\font_roman default
\font_sans default
\font_typewriter default
\font_default_family default
\use_non_tex_fonts false
\font_sc false
\font_osf false
\font_sf_scale 100
\font_tt_scale 100

\graphics default
\default_output_format default
\output_sync 0
\bibtex_command default
\index_command default
\paperfontsize default
\use_hyperref false
\papersize default
\use_geometry false
\use_amsmath 1
\use_esint 1
\use_mhchem 1
\use_mathdots 1
\cite_engine basic
\use_bibtopic false
\use_indices false
\paperorientation portrait
\suppress_date false
\use_refstyle 1
\branch Example
\selected 0
\filename_suffix 0
\color #faf0e6
\end_branch
\index Index
\shortcut idx
\color #008000
\end_index
\secnumdepth 3
\tocdepth 3
\paragraph_separation indent
\paragraph_indentation default
\quotes_language english
\papercolumns 1
\papersides 1
\paperpagestyle default
\tracking_changes false
\output_changes false
\html_math_output 0
\html_css_as_file 0
\html_be_strict false
\end_header

\begin_body
HEAD;
for($t=1;$t<=$objCount;$t++){
    if($t===1){
        $head = 'Subsection';
    }else{
        $head = 'Subsubsection';
    }

$echo=<<<HEAD

\begin_layout $head
{$obj[$t]['name']}
\begin_inset Tabular


<lyxtabular version="3" rows="{$obj[$t]['pNum']}" columns="5">
<features islongtable="true" firstHeadTopDL="true" firstHeadBottomDL="true" headTopDL="true" headBottomDL="true" longtabularalignment="center">
<column alignment="left" valignment="top" width="3.2cm">
<column alignment="left" valignment="top" width="1.7cm">
<column alignment="left" valignment="top" width="2cm">
<column alignment="left" valignment="top" width="6.5cm">
<column alignment="left" valignment="top" width="1.6cm">
<row endhead="true" endfirsthead="true">
<cell alignment="left" valignment="top" topline="true" bottomline="true" leftline="true" usebox="none">
\begin_inset Text

\begin_layout Plain Layout

\series bold
\size footnotesize
Parameter 
\end_layout

\end_inset
</cell>
<cell alignment="left" valignment="top" topline="true" bottomline="true" leftline="true" usebox="none">
\begin_inset Text

\begin_layout Plain Layout

\series bold
\size footnotesize
Data Type
\end_layout

\end_inset
</cell>
<cell alignment="left" valignment="top" topline="true" bottomline="true" leftline="true" usebox="none">
\begin_inset Text

\begin_layout Plain Layout

\series bold
\size footnotesize
Required?
\end_layout

\end_inset
</cell>
<cell alignment="left" valignment="top" topline="true" bottomline="true" leftline="true" usebox="none">
\begin_inset Text

\begin_layout Plain Layout

\series bold
\size footnotesize
Brief description
\end_layout

\end_inset
</cell>
<cell alignment="left" valignment="top" topline="true" bottomline="true" leftline="true" rightline="true" usebox="none">
\begin_inset Text

\begin_layout Plain Layout

\series bold
\size footnotesize
Location
\end_layout

\end_inset
</cell>
</row>
HEAD;

for ($row = 1; $row < $obj[$t]['pNum']; $row++) {
    if ($row === ($obj[$t]['pNum'] - 1)) {
        $bottomline = 'bottomline="true"';
    } else {
        $bottomline = '';
    }
    $echo.=<<<ROW

<row>
<cell alignment="left" valignment="top" topline="true" bottomline="true" leftline="true" usebox="none">
\begin_inset Text

\begin_layout Plain Layout

\size footnotesize
{$obj[$t]['param'][$row]['name']}
\end_layout

\end_inset
</cell>
<cell alignment="left" valignment="top" topline="true" bottomline="true" leftline="true" usebox="none">
\begin_inset Text

\begin_layout Plain Layout

\size footnotesize
{$obj[$t]['param'][$row]['type']}
\end_layout

\end_inset
</cell>
<cell alignment="left" valignment="top" topline="true" bottomline="true" leftline="true" usebox="none">
\begin_inset Text

\begin_layout Plain Layout

\size footnotesize
{$obj[$t]['param'][$row]['require']}
\end_layout

\end_inset
</cell>
<cell alignment="left" valignment="top" topline="true" bottomline="true" leftline="true" usebox="none">
\begin_inset Text

\begin_layout Plain Layout

\size footnotesize
{$obj[$t]['param'][$row]['description']}
\end_layout

\end_inset
</cell>
<cell alignment="left" valignment="top" topline="true" $bottomline leftline="true" rightline="true" usebox="none">
\begin_inset Text

\begin_layout Plain Layout

\size footnotesize
{$obj[$t]['param'][$row]['location']}
\end_layout

\end_inset
</cell>
</row>       
ROW;
}

echo str_replace("\e", "\\e", $echo);


echo <<<'FOOT'
 
</lyxtabular>

\end_inset


\end_layout
FOOT;
}

echo <<<'FOOT'

\end_body
\end_document



FOOT;
?>
