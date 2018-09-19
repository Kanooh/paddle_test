<?php
/**
 * @file
 * paddle_glossary.features.filter.inc
 */

/**
 * Implements hook_filter_default_formats().
 */
function paddle_glossary_filter_default_formats() {
  $formats = array();

  // Exported format: Glossary Full HTML.
  $formats['glossary_full_html'] = array(
    'format' => 'glossary_full_html',
    'name' => 'Glossary Full HTML',
    'cache' => 1,
    'status' => 1,
    'weight' => 0,
    'filters' => array(
      'paddle_text_formats' => array(
        'weight' => -49,
        'status' => 1,
        'settings' => array(),
      ),
      'paddle_scald_filter' => array(
        'weight' => -48,
        'status' => 1,
        'settings' => array(),
      ),
      'htmlpurifier_advanced' => array(
        'weight' => -47,
        'status' => 1,
        'settings' => array(
          'htmlpurifier_help' => 1,
          'htmlpurifier_advanced_config' => array(
            'Null_Attr.AllowedClasses' => 1,
            'Attr.AllowedFrameTargets' => '_blank
_self
_top',
            'Attr.AllowedRel' => '',
            'Attr.AllowedRev' => '',
            'Null_Attr.ClassUseCDATA' => 1,
            'Null_Attr.DefaultImageAlt' => 1,
            'Attr.DefaultInvalidImage' => '',
            'Attr.DefaultInvalidImageAlt' => 'Invalid image',
            'Attr.DefaultTextDir' => 'ltr',
            'Attr.EnableID' => 1,
            'Attr.ForbiddenClasses' => '',
            'Attr.IDBlacklist' => '',
            'Null_Attr.IDBlacklistRegexp' => 1,
            'Attr.IDPrefix' => '',
            'Attr.IDPrefixLocal' => '',
            'AutoFormat.AutoParagraph' => 1,
            'AutoFormat.Custom' => '',
            'AutoFormat.DisplayLinkURI' => 0,
            'AutoFormat.Linkify' => 1,
            'AutoFormat.PurifierLinkify.DocURL' => '#%s',
            'AutoFormat.PurifierLinkify' => 0,
            'AutoFormat.RemoveEmpty.RemoveNbsp.Exceptions' => 'td
th',
            'AutoFormat.RemoveEmpty.RemoveNbsp' => 0,
            'AutoFormat.RemoveEmpty' => 0,
            'AutoFormat.RemoveSpansWithoutAttributes' => 0,
            'CSS.AllowImportant' => 0,
            'CSS.AllowTricky' => 0,
            'Null_CSS.AllowedFonts' => 1,
            'Null_CSS.AllowedProperties' => 1,
            'CSS.ForbiddenProperties' => '',
            'CSS.MaxImgLength' => '1200px',
            'CSS.Proprietary' => 0,
            'CSS.Trusted' => 0,
            'Cache.DefinitionImpl' => 'Drupal',
            'Null_Cache.SerializerPath' => 1,
            'Cache.SerializerPermissions' => 493,
            'Core.AggressivelyFixLt' => 1,
            'Core.AllowHostnameUnderscore' => 0,
            'Core.CollectErrors' => 0,
            'Core.ColorKeywords' => 'maroon:#800000
red:#FF0000
orange:#FFA500
yellow:#FFFF00
olive:#808000
purple:#800080
fuchsia:#FF00FF
white:#FFFFFF
lime:#00FF00
green:#008000
navy:#000080
blue:#0000FF
aqua:#00FFFF
teal:#008080
black:#000000
silver:#C0C0C0
gray:#808080
',
            'Core.ConvertDocumentToFragment' => 1,
            'Core.DirectLexLineNumberSyncInterval' => 0,
            'Core.DisableExcludes' => 0,
            'Core.EnableIDNA' => 0,
            'Core.Encoding' => 'utf-8',
            'Core.EscapeInvalidChildren' => 0,
            'Core.EscapeInvalidTags' => 0,
            'Core.EscapeNonASCIICharacters' => 0,
            'Core.HiddenElements' => 'script
style',
            'Core.Language' => 'en',
            'Null_Core.LexerImpl' => 1,
            'Null_Core.MaintainLineNumbers' => 1,
            'Core.NormalizeNewlines' => 1,
            'Core.RemoveInvalidImg' => 1,
            'Core.RemoveProcessingInstructions' => 0,
            'Null_Core.RemoveScriptContents' => 1,
            'Filter.Custom' => '',
            'Filter.ExtractStyleBlocks.Escaping' => 1,
            'Null_Filter.ExtractStyleBlocks.Scope' => 1,
            'Null_Filter.ExtractStyleBlocks.TidyImpl' => 1,
            'Filter.ExtractStyleBlocks' => 0,
            'Filter.YouTube' => 1,
            'HTML.Allowed' => 'p[class|id]
img[src|title|style|alt|align|vspace|hspace|width|height|class]
a[class|id|href|rel|target|name|title]
ul[class|id]
li[class|id]
strong
em
u
s
sup
sub
ol[class|id]
iframe[class|id|name|title|height|width|style|src|scrolling|frameborder]
div[class|id]
pre
address
h2[class|id]
h3[class|id]
h4[class|id]
h5[class|id]
blockquote
table[class|id|align|border|cellpadding|cellspacing|style|summary|dir|width]
td[class|id|colspan|rowspan|style]
tr[class|id|style]
th[class|id|scope|style]
br
caption
thead[class|style]
tbody[class|style]
tfoot[class|style]
hr
span[class|contenteditable]
video[controls|width|height|class|poster|preload|style]
source[type|src]
track[kind|src|srclang]',
            'Null_HTML.AllowedAttributes' => 1,
            'HTML.AllowedComments' => '',
            'Null_HTML.AllowedCommentsRegexp' => 1,
            'Null_HTML.AllowedElements' => 1,
            'Null_HTML.AllowedModules' => 1,
            'HTML.Attr.Name.UseCDATA' => 0,
            'HTML.BlockWrapper' => 'p',
            'HTML.CoreModules' => 'Structure
Text
Hypertext
List
NonXMLCommonAttributes
XMLCommonAttributes
CommonAttributes',
            'Null_HTML.CustomDoctype' => 1,
            'HTML.Doctype' => 'XHTML 1.0 Transitional',
            'HTML.FlashAllowFullScreen' => 0,
            'HTML.ForbiddenAttributes' => '',
            'HTML.ForbiddenElements' => 'applet
embed
form
object
script',
            'HTML.MaxImgLength' => 1200,
            'HTML.Nofollow' => 0,
            'HTML.Parent' => 'div',
            'HTML.Proprietary' => 0,
            'HTML.SafeEmbed' => 0,
            'HTML.SafeIframe' => 1,
            'HTML.SafeObject' => 0,
            'HTML.SafeScripting' => '',
            'HTML.Strict' => 0,
            'HTML.TargetBlank' => 0,
            'HTML.TidyAdd' => '',
            'HTML.TidyLevel' => 'medium',
            'HTML.TidyRemove' => '',
            'HTML.Trusted' => 0,
            'HTML.XHTML' => 1,
            'Output.CommentScriptContents' => 1,
            'Output.FixInnerHTML' => 1,
            'Output.FlashCompat' => 0,
            'Null_Output.Newline' => 1,
            'Output.SortAttr' => 0,
            'Output.TidyFormat' => 0,
            'Test.ForceNoIconv' => 0,
            'URI.AllowedSchemes' => 'http
https
mailto
ftp
nntp
news
sip',
            'Null_URI.Base' => 1,
            'URI.DefaultScheme' => 'http',
            'URI.Disable' => 0,
            'URI.DisableExternal' => 0,
            'URI.DisableExternalResources' => 0,
            'URI.DisableResources' => 0,
            'Null_URI.Host' => 1,
            'URI.HostBlacklist' => '',
            'URI.MakeAbsolute' => 0,
            'Null_URI.Munge' => 1,
            'URI.MungeResources' => 0,
            'Null_URI.MungeSecretKey' => 1,
            'URI.OverrideAllowedSchemes' => 1,
            'URI.SafeIframeRegexp' => '%.*%',
          ),
        ),
      ),
      'filter_url' => array(
        'weight' => -46,
        'status' => 1,
        'settings' => array(
          'filter_url_length' => 72,
        ),
      ),
      'ckeditor_link_filter' => array(
        'weight' => -45,
        'status' => 1,
        'settings' => array(),
      ),
      'filter_htmlcorrector' => array(
        'weight' => -44,
        'status' => 1,
        'settings' => array(),
      ),
      'remove_p_wrapping_img' => array(
        'weight' => -43,
        'status' => 1,
        'settings' => array(),
      ),
    ),
  );

  return $formats;
}
