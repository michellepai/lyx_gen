<?php

if (isset($_GET['source']))
    exit('<!DOCTYPE HTML><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>OpenTBS plug-in for TinyButStrong - demo source</title></head><body>' . highlight_file(__FILE__, true) . '</body></html>');

if (isset($_POST['download_method']) && $_POST['download_method'] == 'latex') {
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=op_summary.lyx");

    $opCount = 1;
    $op_summary = array();
    do {
        array_push($op_summary, $_POST["ops" . $opCount . "_name"]);
        $op_summary[$opCount] = array(
            'ops_name' => $_POST["ops" . $opCount . "_name"],
            'ops_base_url' => $_POST["ops" . $opCount . "_base_url"],
            'ops_verb' => $_POST["ops" . $opCount . "_verb"],
            'ops_type_desc' => $_POST["ops" . $opCount . "_type_desc"],
                //'ops' . $opCount . '_auth' => $_POST["ops" . $opCount . "_auth"],
                //'ops' . $opCount . '_oauth_scope' => $_POST["ops" . $opCount . "_oauth_scope"]
        );
        $opCount++;
    } while (isset($_POST["ops" . $opCount . "_name"]));
    //var_dump($op_summary);
    //$opCount++;
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

\begin_layout Standard
\begin_inset Tabular
HEAD;
    echo <<<HEAD

<lyxtabular version="3" rows="$opCount" columns="4">
<features islongtable="true" firstHeadTopDL="true" firstHeadBottomDL="true" headTopDL="true" headBottomDL="true" longtabularalignment="center">
<column alignment="left" valignment="top" width="4cm">
<column alignment="left" valignment="top" width="7cm">
<column alignment="left" valignment="top" width="2cm">
<column alignment="left" valignment="top" width="2cm">
<row endhead="true" endfirsthead="true">
<cell alignment="left" valignment="top" topline="true" bottomline="true" leftline="true" usebox="none">
\begin_inset Text

\begin_layout Plain Layout

\series bold
\size footnotesize
Operation Name
\end_layout

\end_inset
</cell>
<cell alignment="left" valignment="top" topline="true" bottomline="true" leftline="true" usebox="none">
\begin_inset Text

\begin_layout Plain Layout

\series bold
\size footnotesize
Resource URI relative to https://api.att.com
\end_layout

\end_inset
</cell>
<cell alignment="left" valignment="top" topline="true" bottomline="true" leftline="true" usebox="none">
\begin_inset Text

\begin_layout Plain Layout

\series bold
\size footnotesize
HTTP Verb
\end_layout

\end_inset
</cell>
<cell alignment="left" valignment="top" topline="true" bottomline="true" leftline="true" rightline="true" usebox="none">
\begin_inset Text

\begin_layout Plain Layout

\series bold
\size footnotesize
Operation Type
\end_layout

\end_inset
</cell>
</row>
HEAD;

    for ($row = 1; $row < $opCount; $row++) {
        if ($row === ($opCount-1)) {
            $bottomline = 'bottomline="true"';
        } else {
            $bottomline = '';
        }
        echo <<<SUM

<row>
<cell alignment="left" valignment="top" topline="true" bottomline="true" leftline="true" usebox="none">
\begin_inset Text

\begin_layout Plain Layout

\size footnotesize
{$op_summary[$row]['ops_name']}
\end_layout

\end_inset
</cell>
<cell alignment="left" valignment="top" topline="true" bottomline="true" leftline="true" usebox="none">
\begin_inset Text

\begin_layout Plain Layout

\size footnotesize
{$op_summary[$row]['ops_base_url']}
\end_layout

\end_inset
</cell>
<cell alignment="left" valignment="top" topline="true" bottomline="true" leftline="true" usebox="none">
\begin_inset Text

\begin_layout Plain Layout

\size footnotesize
{$op_summary[$row]['ops_verb']}
\end_layout

\end_inset
</cell>
<cell alignment="left" valignment="top" topline="true" $bottomline leftline="true" rightline="true" usebox="none">
\begin_inset Text

\begin_layout Plain Layout

\size footnotesize
{$op_summary[$row]['ops_type_desc']}
\end_layout

\end_inset
</cell>
</row>

SUM;
    }

    echo <<<'FOOT'
 
</lyxtabular>

\end_inset


\end_layout

\end_body
\end_document



FOOT;
}

//docx
if (isset($_POST['download_method']) && $_POST['download_method'] == 'docx') {
    if (!isset($_POST['btn_go']))
        exit("You must use <a href='demo.html'>demo.html</a>");
    $template = (isset($_POST['tpl'])) ? $_POST['tpl'] : '';
    $template = basename($template); // for security
    $info = pathinfo($template);

    if (substr($template, 0, 5) !== 'demo_')
        exit("Wrong file.");
    if (!file_exists($template))
        exit("The asked template does not exist.");

    $script = $info['filename'] . '.php';
    include($script);
}

//cvs
if (isset($_POST['download_method']) && $_POST['download_method'] == 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=api_spec.csv');
    foreach ($_POST as $p) {
        echo $p . ',';
    }
}

if (isset($_POST['download_method']) && $_POST['download_method'] == 'json') {
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename=api_spec.txt');
    echo json_encode($_POST);
}
?>
