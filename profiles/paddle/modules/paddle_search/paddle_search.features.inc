<?php
/**
 * @file
 * paddle_search.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function paddle_search_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "facetapi" && $api == "facetapi_defaults") {
    return array("version" => "1");
  }
  if ($module == "page_manager" && $api == "pages_default") {
    return array("version" => "1");
  }
  if ($module == "pm_existing_pages" && $api == "pm_existing_pages") {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_image_default_styles().
 */
function paddle_search_image_default_styles() {
  $styles = array();

  // Exported image style: paddle_search_featured_image.
  $styles['paddle_search_featured_image'] = array(
    'label' => 'Paddle Search Featured Image',
    'effects' => array(
      3 => array(
        'name' => 'manualcrop_reuse',
        'data' => array(
          'reuse_crop_style' => '4_3',
          'apply_all_effects' => 0,
        ),
        'weight' => 0,
      ),
      1 => array(
        'name' => 'image_scale_and_crop',
        'data' => array(
          'width' => 120,
          'height' => 90,
        ),
        'weight' => 1,
      ),
    ),
  );

  return $styles;
}

/**
 * Implements hook_default_search_api_autocomplete_search().
 */
function paddle_search_default_search_api_autocomplete_search() {
  $items = array();
  $items['search_api_page_search'] = entity_import('search_api_autocomplete_search', '{
    "machine_name" : "search_api_page_search",
    "name" : "Search",
    "index_id" : "node_index",
    "type" : "search_api_page",
    "enabled" : "1",
    "options" : {
      "custom" : { "page_id" : "search" },
      "result count" : true,
      "fields" : [ "search_api_aggregation_2" ],
      "results" : 0,
      "min_length" : "3",
      "submit_button_selector" : ":submit"
    }
  }');
  return $items;
}

/**
 * Implements hook_default_search_api_index().
 */
function paddle_search_default_search_api_index() {
  $items = array();
  $items['node_index'] = entity_import('search_api_index', '{
    "name" : "Node index",
    "machine_name" : "node_index",
    "description" : null,
    "server" : "solr_server",
    "item_type" : "node",
    "options" : {
      "index_directly" : 0,
      "cron_limit" : 50,
      "data_alter_callbacks" : {
        "search_api_alter_bundle_filter" : {
          "status" : 0,
          "weight" : "-50",
          "settings" : { "default" : "1", "bundles" : [] }
        },
        "search_api_alter_language_control" : {
          "status" : 0,
          "weight" : "-49",
          "settings" : { "lang_field" : "", "languages" : [] }
        },
        "search_api_alter_node_access" : { "status" : 1, "weight" : "-48", "settings" : [] },
        "search_api_alter_node_status" : { "status" : 1, "weight" : "-47", "settings" : [] },
        "search_api_alter_add_hierarchy" : {
          "status" : 1,
          "weight" : "-46",
          "settings" : { "fields" : { "field_paddle_general_tags:parent" : "field_paddle_general_tags:parent" } }
        },
        "panelizer" : { "status" : 1, "weight" : "-45", "settings" : [] },
        "search_api_alter_add_url" : { "status" : 0, "weight" : "-43", "settings" : [] },
        "search_api_alter_add_aggregation" : {
          "status" : 1,
          "weight" : "-42",
          "settings" : { "fields" : {
              "search_api_aggregation_1" : {
                "name" : "tags",
                "type" : "fulltext",
                "fields" : [ "field_paddle_general_tags:name", "field_paddle_tags:name" ],
                "description" : "A Fulltext aggregation of the following fields: General tags \\u00bb Name, Tags \\u00bb Name."
              },
              "search_api_aggregation_2" : {
                "name" : "Autocomplete",
                "type" : "fulltext",
                "fields" : [
                  "title",
                  "search_api_aggregation_1",
                  "search_api_panelizer_content",
                  "body:summary"
                ],
                "description" : "A Fulltext aggregation of the following fields: Title, tags, Panelizer \\u0026quot;Full page override\\u0026quot; HTML output, The main body text \\u00bb Summary."
              },
              "search_api_aggregation_3" : {
                "name" : "NGram",
                "type" : "fulltext",
                "fields" : [
                  "title",
                  "search_api_aggregation_1",
                  "search_api_panelizer_content",
                  "body:summary"
                ],
                "description" : "A Fulltext aggregation of the following fields: Title, tags, Panelizer \\u0026quot;Full page override\\u0026quot; HTML output, The main body text \\u00bb Summary."
              },
              "search_api_aggregation_4" : {
                "name" : "Title as searchable Fulltext",
                "type" : "fulltext",
                "fields" : [ "title" ],
                "description" : "A Fulltext aggregation of the following fields: Title."
              }
            }
          }
        },
        "search_api_alter_add_viewed_entity" : { "status" : 0, "weight" : "-41", "settings" : { "mode" : "full" } },
        "search_api_attachments_alter_settings" : {
          "status" : 0,
          "weight" : "0",
          "settings" : {
            "excluded_extensions" : "aif art avi bmp gif ico mov oga ogv png psd ra ram rgb flv",
            "number_indexed" : "0",
            "max_file_size" : "0",
            "excluded_private" : 1
          }
        },
        "paddle_search_search_api_attachments_alter_settings" : {
          "status" : 1,
          "weight" : "0",
          "settings" : {
            "excluded_extensions" : "aif art avi bmp gif ico mov oga ogv png psd ra ram rgb flv",
            "number_indexed" : "0",
            "max_file_size" : "10 MB",
            "excluded_private" : 0
          }
        }
      },
      "processors" : {
        "search_api_case_ignore" : {
          "status" : 1,
          "weight" : "0",
          "settings" : { "fields" : {
              "title" : true,
              "field_paddle_cirro_action_strats" : true,
              "field_paddle_cirro_policy_themes" : true,
              "field_paddle_cirro_settings" : true,
              "field_paddle_kce_authors" : true,
              "field_paddle_kce_keywords" : true,
              "field_paddle_kce_publication_y" : true,
              "attachments_paddle_scald_file" : true,
              "search_api_aggregation_2" : true,
              "search_api_aggregation_3" : true,
              "search_api_aggregation_4" : true,
              "search_api_panelizer_content" : true,
              "body:value" : true,
              "body:summary" : true,
              "field_paddle_general_tags:name" : true,
              "field_paddle_tags:name" : true
            }
          }
        },
        "search_api_html_filter" : {
          "status" : 1,
          "weight" : "10",
          "settings" : {
            "fields" : {
              "title" : true,
              "field_paddle_cirro_action_strats" : true,
              "field_paddle_cirro_policy_themes" : true,
              "field_paddle_cirro_settings" : true,
              "field_paddle_kce_authors" : true,
              "field_paddle_kce_keywords" : true,
              "field_paddle_kce_publication_y" : true,
              "attachments_paddle_scald_file" : true,
              "search_api_aggregation_2" : true,
              "search_api_aggregation_3" : true,
              "search_api_aggregation_4" : true,
              "search_api_panelizer_content" : true,
              "body:value" : true,
              "body:summary" : true
            },
            "title" : 1,
            "alt" : 1,
            "tags" : "h1 = 5\\r\\nh2 = 3\\r\\nh3 = 2\\r\\nstrong = 2\\r\\nb = 2\\r\\nem = 1.5\\r\\nu = 1.5"
          }
        },
        "search_api_tokenizer" : {
          "status" : 1,
          "weight" : "20",
          "settings" : {
            "fields" : {
              "attachments_paddle_scald_file" : true,
              "search_api_aggregation_2" : true,
              "search_api_aggregation_3" : true,
              "search_api_aggregation_4" : true,
              "search_api_panelizer_content" : true,
              "body:value" : true,
              "body:summary" : true
            },
            "spaces" : "[^\\\\p{L}\\\\p{N}]",
            "ignorable" : "[-]"
          }
        },
        "search_api_stopwords" : {
          "status" : 1,
          "weight" : "30",
          "settings" : {
            "fields" : {
              "title" : true,
              "search_api_aggregation_4" : true,
              "search_api_panelizer_content" : true,
              "body:value" : true,
              "body:summary" : true
            },
            "file" : "",
            "stopwords" : "aan\\r\\naangaande\\r\\naangezien\\r\\nachte\\r\\nachter\\r\\nachterna\\r\\naf\\r\\nafgelopen\\r\\nal\\r\\naldaar\\r\\naldus\\r\\nalhoewel\\r\\nalias\\r\\nalle\\r\\nallebei\\r\\nalleen\\r\\nalles\\r\\nals\\r\\nalsnog\\r\\naltijd\\r\\naltoos\\r\\nander\\r\\nandere\\r\\nanders\\r\\nanderszins\\r\\nbeetje\\r\\nbehalve\\r\\nbehoudens\\r\\nbeide\\r\\nbeiden\\r\\nben\\r\\nbeneden\\r\\nbent\\r\\nbepaald\\r\\nbetreffende\\r\\nbij\\r\\nbijna\\r\\nbijv\\r\\nbinnen\\r\\nbinnenin\\r\\nblijkbaar\\r\\nblijken\\r\\nboven\\r\\nbovenal\\r\\nbovendien\\r\\nbovengenoemd\\r\\nbovenstaand\\r\\nbovenvermeld\\r\\nbuiten\\r\\nbv\\r\\ndaar\\r\\ndaardoor\\r\\ndaarheen\\r\\ndaarin\\r\\ndaarna\\r\\ndaarnet\\r\\ndaarom\\r\\ndaarop\\r\\ndaaruit\\r\\ndaarvanlangs\\r\\ndan\\r\\ndat\\r\\nde\\r\\ndeden\\r\\ndeed\\r\\nder\\r\\nderde\\r\\nderhalve\\r\\ndertig\\r\\ndeze\\r\\ndhr\\r\\ndie\\r\\ndikwijls\\r\\ndit\\r\\ndoch\\r\\ndoe\\r\\ndoen\\r\\ndoet\\r\\ndoor\\r\\ndoorgaand\\r\\ndrie\\r\\nduizend\\r\\ndus\\r\\nechter\\r\\neen\\r\\neens\\r\\neer\\r\\neerdat\\r\\neerder\\r\\neerlang\\r\\neerst\\r\\neerste\\r\\neigen\\r\\neigenlijk\\r\\nelk\\r\\nelke\\r\\nen\\r\\nenig\\r\\nenige\\r\\nenigszins\\r\\nenkel\\r\\ner\\r\\nerdoor\\r\\nerg\\r\\nergens\\r\\netc\\r\\netcetera\\r\\neven\\r\\neveneens\\r\\nevenwel\\r\\ngauw\\r\\nge\\r\\ngedurende\\r\\ngeen\\r\\ngehad\\r\\ngekund\\r\\ngeleden\\r\\ngelijk\\r\\ngemoeten\\r\\ngemogen\\r\\ngenoeg\\r\\ngeweest\\r\\ngewoon\\r\\ngewoonweg\\r\\nhaar\\r\\nhaarzelf\\r\\nhad\\r\\nhadden\\r\\nhare\\r\\nheb\\r\\nhebben\\r\\nhebt\\r\\nhedden\\r\\nheeft\\r\\nheel\\r\\nhem\\r\\nhemzelf\\r\\nhen\\r\\nhet\\r\\nhetzelfde\\r\\nhier\\r\\nhierbeneden\\r\\nhierboven\\r\\nhierin\\r\\nhierna\\r\\nhierom\\r\\nhij\\r\\nhijzelf\\r\\nhoe\\r\\nhoewel\\r\\nhonderd\\r\\nhun\\r\\nhunne\\r\\nieder\\r\\niedere\\r\\niedereen\\r\\niemand\\r\\niets\\r\\nik\\r\\nikzelf\\r\\nin\\r\\ninderdaad\\r\\ninmiddels\\r\\nintussen\\r\\ninzake\\r\\nis\\r\\nja\\r\\nje\\r\\njezelf\\r\\njij\\r\\njijzelf\\r\\njou\\r\\njouw\\r\\njouwe\\r\\njuist\\r\\njullie\\r\\nkan\\r\\nklaar\\r\\nkon\\r\\nkonden\\r\\nkrachtens\\r\\nkun\\r\\nkunnen\\r\\nkunt\\r\\nlaatst\\r\\nlater\\r\\nliever\\r\\nlijken\\r\\nlijkt\\r\\nmaak\\r\\nmaakt\\r\\nmaakte\\r\\nmaakten\\r\\nmaar\\r\\nmag\\r\\nmaken\\r\\nme\\r\\nmeer\\r\\nmeest\\r\\nmeestal\\r\\nmen\\r\\nmet\\r\\nmevr\\r\\nmezelf\\r\\nmij\\r\\nmijn\\r\\nmijnent\\r\\nmijner\\r\\nmijzelf\\r\\nminder\\r\\nmiss\\r\\nmisschien\\r\\nmissen\\r\\nmits\\r\\nmocht\\r\\nmochten\\r\\nmoest\\r\\nmoesten\\r\\nmoet\\r\\nmoeten\\r\\nmogen\\r\\nmr\\r\\nmrs\\r\\nmw\\r\\nna\\r\\nnaar\\r\\nnadat\\r\\nnam\\r\\nnamelijk\\r\\nnee\\r\\nneem\\r\\nnegen\\r\\nnemen\\r\\nnergens\\r\\nnet\\r\\nniemand\\r\\nniet\\r\\nniets\\r\\nniks\\r\\nnoch\\r\\nnochtans\\r\\nnog\\r\\nnogal\\r\\nnooit\\r\\nnu\\r\\nnv\\r\\nof\\r\\nofschoon\\r\\nom\\r\\nomdat\\r\\nomhoog\\r\\nomlaag\\r\\nomstreeks\\r\\nomtrent\\r\\nomver\\r\\nondanks\\r\\nonder\\r\\nondertussen\\r\\nongeveer\\r\\nons\\r\\nonszelf\\r\\nonze\\r\\nonzeker\\r\\nooit\\r\\nook\\r\\nop\\r\\nopnieuw\\r\\nopzij\\r\\nover\\r\\noveral\\r\\novereind\\r\\noverige\\r\\noverigens\\r\\npaar\\r\\npas\\r\\nper\\r\\nprecies\\r\\nrecent\\r\\nredelijk\\r\\nreeds\\r\\nrond\\r\\nrondom\\r\\nsamen\\r\\nsedert\\r\\nsinds\\r\\nsindsdien\\r\\nslechts\\r\\nsommige\\r\\nspoedig\\r\\nsteeds\\r\\ntamelijk\\r\\nte\\r\\ntegen\\r\\ntegenover\\r\\ntenzij\\r\\nterwijl\\r\\nthans\\r\\ntien\\r\\ntiende\\r\\ntijdens\\r\\ntja\\r\\ntoch\\r\\ntoe\\r\\ntoen\\r\\ntoenmaals\\r\\ntoenmalig\\r\\ntot\\r\\ntotdat\\r\\ntussen\\r\\ntwee\\r\\ntweede\\r\\nu\\r\\nuit\\r\\nuitgezonderd\\r\\nuw\\r\\nvaak\\r\\nvaakwat\\r\\nvan\\r\\nvanaf\\r\\nvandaan\\r\\nvanuit\\r\\nvanwege\\r\\nveel\\r\\nveeleer\\r\\nveertig\\r\\nverder\\r\\nverscheidene\\r\\nverschillende\\r\\nvervolgens\\r\\nvia\\r\\nvier\\r\\nvierde\\r\\nvijf\\r\\nvijfde\\r\\nvijftig\\r\\nvol\\r\\nvolgend\\r\\nvolgens\\r\\nvoor\\r\\nvooraf\\r\\nvooral\\r\\nvooralsnog\\r\\nvoorbij\\r\\nvoordat\\r\\nvoordezen\\r\\nvoordien\\r\\nvoorheen\\r\\nvoorop\\r\\nvoorts\\r\\nvooruit\\r\\nvrij\\r\\nvroeg\\r\\nwaar\\r\\nwaarom\\r\\nwaarschijnlijk\\r\\nwanneer\\r\\nwant\\r\\nwaren\\r\\nwas\\r\\nwat\\r\\nwe\\r\\nwederom\\r\\nweer\\r\\nweg\\r\\nwegens\\r\\nweinig\\r\\nwel\\r\\nweldra\\r\\nwelk\\r\\nwelke\\r\\nwerd\\r\\nwerden\\r\\nwerder\\r\\nwezen\\r\\nwhatever\\r\\nwie\\r\\nwiens\\r\\nwier\\r\\nwij\\r\\nwijzelf\\r\\nwil\\r\\nwilden\\r\\nwillen\\r\\nword\\r\\nworden\\r\\nwordt\\r\\nzal\\r\\nze\\r\\nzei\\r\\nzeker\\r\\nzelf\\r\\nzelfde\\r\\nzelfs\\r\\nzes\\r\\nzeven\\r\\nzich\\r\\nzichzelf\\r\\nzij\\r\\nzijn\\r\\nzijne\\r\\nzijzelf\\r\\nzo\\r\\nzoals\\r\\nzodat\\r\\nzodra\\r\\nzonder\\r\\nzou\\r\\nzouden\\r\\nzowat\\r\\nzulk\\r\\nzulke\\r\\nzullen\\r\\nzult\\r\\na\\r\\nabord\\r\\nabsolument\\r\\nafin\\r\\nah\\r\\nai\\r\\naie\\r\\naient\\r\\naies\\r\\nailleurs\\r\\nainsi\\r\\nait\\r\\nallaient\\r\\nallo\\r\\nallons\\r\\nall\\u00f4\\r\\nalors\\r\\nanterieur\\r\\nanterieure\\r\\nanterieures\\r\\napres\\r\\napr\\u00e8s\\r\\nas\\r\\nassez\\r\\nattendu\\r\\nau\\r\\naucun\\r\\naucune\\r\\naucuns\\r\\naujourd\\r\\naujourd\\u0027hui\\r\\naupres\\r\\nauquel\\r\\naura\\r\\naurai\\r\\nauraient\\r\\naurais\\r\\naurait\\r\\nauras\\r\\naurez\\r\\nauriez\\r\\naurions\\r\\naurons\\r\\nauront\\r\\naussi\\r\\nautre\\r\\nautrefois\\r\\nautrement\\r\\nautres\\r\\nautrui\\r\\naux\\r\\nauxquelles\\r\\nauxquels\\r\\navaient\\r\\navais\\r\\navait\\r\\navant\\r\\navec\\r\\navez\\r\\naviez\\r\\navions\\r\\navoir\\r\\navons\\r\\nayant\\r\\nayez\\r\\nayons\\r\\nb\\r\\nbah\\r\\nbas\\r\\nbasee\\r\\nbat\\r\\nbeau\\r\\nbeaucoup\\r\\nbien\\r\\nbigre\\r\\nbon\\r\\nboum\\r\\nbravo\\r\\nbrrr\\r\\nc\\r\\ncar\\r\\nce\\r\\nceci\\r\\ncela\\r\\ncelle\\r\\ncelle-ci\\r\\ncelle-l\\u00e0\\r\\ncelles\\r\\ncelles-ci\\r\\ncelles-l\\u00e0\\r\\ncelui\\r\\ncelui-ci\\r\\ncelui-l\\u00e0\\r\\ncel\\u00e0\\r\\ncent\\r\\ncependant\\r\\ncertain\\r\\ncertaine\\r\\ncertaines\\r\\ncertains\\r\\ncertes\\r\\nces\\r\\ncet\\r\\ncette\\r\\nceux\\r\\nceux-ci\\r\\nceux-l\\u00e0\\r\\nchacun\\r\\nchacune\\r\\nchaque\\r\\ncher\\r\\nchers\\r\\nchez\\r\\nchiche\\r\\nchut\\r\\nch\\u00e8re\\r\\nch\\u00e8res\\r\\nci\\r\\ncinq\\r\\ncinquantaine\\r\\ncinquante\\r\\ncinquanti\\u00e8me\\r\\ncinqui\\u00e8me\\r\\nclac\\r\\nclic\\r\\ncombien\\r\\ncomme\\r\\ncomment\\r\\ncomparable\\r\\ncomparables\\r\\ncompris\\r\\nconcernant\\r\\ncontre\\r\\ncouic\\r\\ncrac\\r\\nd\\r\\nda\\r\\ndans\\r\\nde\\r\\ndebout\\r\\ndedans\\r\\ndehors\\r\\ndeja\\r\\ndel\\u00e0\\r\\ndepuis\\r\\ndernier\\r\\nderniere\\r\\nderriere\\r\\nderri\\u00e8re\\r\\ndes\\r\\ndesormais\\r\\ndesquelles\\r\\ndesquels\\r\\ndessous\\r\\ndessus\\r\\ndeux\\r\\ndeuxi\\u00e8me\\r\\ndeuxi\\u00e8mement\\r\\ndevant\\r\\ndevers\\r\\ndevra\\r\\ndevrait\\r\\ndifferent\\r\\ndifferentes\\r\\ndifferents\\r\\ndiff\\u00e9rent\\r\\ndiff\\u00e9rente\\r\\ndiff\\u00e9rentes\\r\\ndiff\\u00e9rents\\r\\ndire\\r\\ndirecte\\r\\ndirectement\\r\\ndit\\r\\ndite\\r\\ndits\\r\\ndivers\\r\\ndiverse\\r\\ndiverses\\r\\ndix\\r\\ndix-huit\\r\\ndix-neuf\\r\\ndix-sept\\r\\ndixi\\u00e8me\\r\\ndoit\\r\\ndoivent\\r\\ndonc\\r\\ndont\\r\\ndos\\r\\ndouze\\r\\ndouzi\\u00e8me\\r\\ndring\\r\\ndroite\\r\\ndu\\r\\nduquel\\r\\ndurant\\r\\nd\\u00e8s\\r\\nd\\u00e9but\\r\\nd\\u00e9sormais\\r\\ne\\r\\neffet\\r\\negale\\r\\negalement\\r\\negales\\r\\neh\\r\\nelle\\r\\nelle-m\\u00eame\\r\\nelles\\r\\nelles-m\\u00eames\\r\\nen\\r\\nencore\\r\\nenfin\\r\\nentre\\r\\nenvers\\r\\nenviron\\r\\nes\\r\\nessai\\r\\nest\\r\\net\\r\\netant\\r\\netc\\r\\netre\\r\\neu\\r\\neue\\r\\neues\\r\\neuh\\r\\neurent\\r\\neus\\r\\neusse\\r\\neussent\\r\\neusses\\r\\neussiez\\r\\neussions\\r\\neut\\r\\neux\\r\\neux-m\\u00eames\\r\\nexactement\\r\\nexcept\\u00e9\\r\\nextenso\\r\\nexterieur\\r\\ne\\u00fbmes\\r\\ne\\u00fbt\\r\\ne\\u00fbtes\\r\\nf\\r\\nfais\\r\\nfaisaient\\r\\nfaisant\\r\\nfait\\r\\nfaites\\r\\nfa\\u00e7on\\r\\nferont\\r\\nfi\\r\\nflac\\r\\nfloc\\r\\nfois\\r\\nfont\\r\\nforce\\r\\nfurent\\r\\nfus\\r\\nfusse\\r\\nfussent\\r\\nfusses\\r\\nfussiez\\r\\nfussions\\r\\nfut\\r\\nf\\u00fbmes\\r\\nf\\u00fbt\\r\\nf\\u00fbtes\\r\\ng\\r\\ngens\\r\\nh\\r\\nha\\r\\nhaut\\r\\nhein\\r\\nhem\\r\\nhep\\r\\nhi\\r\\nho\\r\\nhol\\u00e0\\r\\nhop\\r\\nhormis\\r\\nhors\\r\\nhou\\r\\nhoup\\r\\nhue\\r\\nhui\\r\\nhuit\\r\\nhuiti\\u00e8me\\r\\nhum\\r\\nhurrah\\r\\nh\\u00e9\\r\\nh\\u00e9las\\r\\ni\\r\\nici\\r\\nil\\r\\nils\\r\\nimporte\\r\\nj\\r\\nje\\r\\njusqu\\r\\njusque\\r\\njuste\\r\\nk\\r\\nl\\r\\nla\\r\\nlaisser\\r\\nlaquelle\\r\\nlas\\r\\nle\\r\\nlequel\\r\\nles\\r\\nlesquelles\\r\\nlesquels\\r\\nleur\\r\\nleurs\\r\\nlongtemps\\r\\nlors\\r\\nlorsque\\r\\nlui\\r\\nlui-meme\\r\\nlui-m\\u00eame\\r\\nl\\u00e0\\r\\nl\\u00e8s\\r\\nm\\r\\nma\\r\\nmaint\\r\\nmaintenant\\r\\nmais\\r\\nmalgre\\r\\nmalgr\\u00e9\\r\\nmaximale\\r\\nme\\r\\nmeme\\r\\nmemes\\r\\nmerci\\r\\nmes\\r\\nmien\\r\\nmienne\\r\\nmiennes\\r\\nmiens\\r\\nmille\\r\\nmince\\r\\nmine\\r\\nminimale\\r\\nmoi\\r\\nmoi-meme\\r\\nmoi-m\\u00eame\\r\\nmoindres\\r\\nmoins\\r\\nmon\\r\\nmot\\r\\nmoyennant\\r\\nmultiple\\r\\nmultiples\\r\\nm\\u00eame\\r\\nm\\u00eames\\r\\nn\\r\\nna\\r\\nnaturel\\r\\nnaturelle\\r\\nnaturelles\\r\\nne\\r\\nneanmoins\\r\\nnecessaire\\r\\nnecessairement\\r\\nneuf\\r\\nneuvi\\u00e8me\\r\\nni\\r\\nnombreuses\\r\\nnombreux\\r\\nnomm\\u00e9s\\r\\nnon\\r\\nnos\\r\\nnotamment\\r\\nnotre\\r\\nnous\\r\\nnous-m\\u00eames\\r\\nnouveau\\r\\nnouveaux\\r\\nnul\\r\\nn\\u00e9anmoins\\r\\nn\\u00f4tre\\r\\nn\\u00f4tres\\r\\no\\r\\noh\\r\\noh\\u00e9\\r\\noll\\u00e9\\r\\nol\\u00e9\\r\\non\\r\\nont\\r\\nonze\\r\\nonzi\\u00e8me\\r\\nore\\r\\nou\\r\\nouf\\r\\nouias\\r\\noust\\r\\nouste\\r\\noutre\\r\\nouvert\\r\\nouverte\\r\\nouverts\\r\\no|\\r\\no\\u00f9\\r\\np\\r\\npaf\\r\\npan\\r\\npar\\r\\nparce\\r\\nparfois\\r\\nparle\\r\\nparlent\\r\\nparler\\r\\nparmi\\r\\nparole\\r\\nparseme\\r\\npartant\\r\\nparticulier\\r\\nparticuli\\u00e8re\\r\\nparticuli\\u00e8rement\\r\\npas\\r\\npass\\u00e9\\r\\npendant\\r\\npense\\r\\npermet\\r\\npersonne\\r\\npersonnes\\r\\npeu\\r\\npeut\\r\\npeuvent\\r\\npeux\\r\\npff\\r\\npfft\\r\\npfut\\r\\npif\\r\\npire\\r\\npi\\u00e8ce\\r\\nplein\\r\\nplouf\\r\\nplupart\\r\\nplus\\r\\nplusieurs\\r\\nplut\\u00f4t\\r\\npossessif\\r\\npossessifs\\r\\npossible\\r\\npossibles\\r\\npouah\\r\\npour\\r\\npourquoi\\r\\npourrais\\r\\npourrait\\r\\npouvait\\r\\nprealable\\r\\nprecisement\\r\\npremier\\r\\npremi\\u00e8re\\r\\npremi\\u00e8rement\\r\\npres\\r\\nprobable\\r\\nprobante\\r\\nprocedant\\r\\nproche\\r\\npr\\u00e8s\\r\\npsitt\\r\\npu\\r\\npuis\\r\\npuisque\\r\\npur\\r\\npure\\r\\nq\\r\\nqu\\r\\nquand\\r\\nquant\\r\\nquant-\\u00e0-soi\\r\\nquanta\\r\\nquarante\\r\\nquatorze\\r\\nquatre\\r\\nquatre-vingt\\r\\nquatri\\u00e8me\\r\\nquatri\\u00e8mement\\r\\nque\\r\\nquel\\r\\nquelconque\\r\\nquelle\\r\\nquelles\\r\\nquelqu\\u0027un\\r\\nquelque\\r\\nquelques\\r\\nquels\\r\\nqui\\r\\nquiconque\\r\\nquinze\\r\\nquoi\\r\\nquoique\\r\\nr\\r\\nrare\\r\\nrarement\\r\\nrares\\r\\nrelative\\r\\nrelativement\\r\\nremarquable\\r\\nrend\\r\\nrendre\\r\\nrestant\\r\\nreste\\r\\nrestent\\r\\nrestrictif\\r\\nretour\\r\\nrevoici\\r\\nrevoil\\u00e0\\r\\nrien\\r\\ns\\r\\nsa\\r\\nsacrebleu\\r\\nsait\\r\\nsans\\r\\nsapristi\\r\\nsauf\\r\\nse\\r\\nsein\\r\\nseize\\r\\nselon\\r\\nsemblable\\r\\nsemblaient\\r\\nsemble\\r\\nsemblent\\r\\nsent\\r\\nsept\\r\\nsepti\\u00e8me\\r\\nsera\\r\\nserai\\r\\nseraient\\r\\nserais\\r\\nserait\\r\\nseras\\r\\nserez\\r\\nseriez\\r\\nserions\\r\\nserons\\r\\nseront\\r\\nses\\r\\nseul\\r\\nseule\\r\\nseulement\\r\\nsi\\r\\nsien\\r\\nsienne\\r\\nsiennes\\r\\nsiens\\r\\nsinon\\r\\nsix\\r\\nsixi\\u00e8me\\r\\nsoi\\r\\nsoi-m\\u00eame\\r\\nsoient\\r\\nsois\\r\\nsoit\\r\\nsoixante\\r\\nsommes\\r\\nson\\r\\nsont\\r\\nsous\\r\\nsouvent\\r\\nsoyez\\r\\nsoyons\\r\\nspecifique\\r\\nspecifiques\\r\\nspeculatif\\r\\nstop\\r\\nstrictement\\r\\nsubtiles\\r\\nsuffisant\\r\\nsuffisante\\r\\nsuffit\\r\\nsuis\\r\\nsuit\\r\\nsuivant\\r\\nsuivante\\r\\nsuivantes\\r\\nsuivants\\r\\nsuivre\\r\\nsujet\\r\\nsuperpose\\r\\nsur\\r\\nsurtout\\r\\nt\\r\\nta\\r\\ntac\\r\\ntandis\\r\\ntant\\r\\ntardive\\r\\nte\\r\\ntel\\r\\ntelle\\r\\ntellement\\r\\ntelles\\r\\ntels\\r\\ntenant\\r\\ntend\\r\\ntenir\\r\\ntente\\r\\ntes\\r\\ntic\\r\\ntien\\r\\ntienne\\r\\ntiennes\\r\\ntiens\\r\\ntoc\\r\\ntoi\\r\\ntoi-m\\u00eame\\r\\nton\\r\\ntouchant\\r\\ntoujours\\r\\ntous\\r\\ntout\\r\\ntoute\\r\\ntoutefois\\r\\ntoutes\\r\\ntreize\\r\\ntrente\\r\\ntres\\r\\ntrois\\r\\ntroisi\\u00e8me\\r\\ntroisi\\u00e8mement\\r\\ntrop\\r\\ntr\\u00e8s\\r\\ntsoin\\r\\ntsouin\\r\\ntu\\r\\nt\\u00e9\\r\\nu\\r\\nun\\r\\nune\\r\\nunes\\r\\nuniformement\\r\\nunique\\r\\nuniques\\r\\nuns\\r\\nv\\r\\nva\\r\\nvais\\r\\nvaleur\\r\\nvas\\r\\nvers\\r\\nvia\\r\\nvif\\r\\nvifs\\r\\nvingt\\r\\nvivat\\r\\nvive\\r\\nvives\\r\\nvlan\\r\\nvoici\\r\\nvoie\\r\\nvoient\\r\\nvoil\\u00e0\\r\\nvont\\r\\nvos\\r\\nvotre\\r\\nvous\\r\\nvous-m\\u00eames\\r\\nvu\\r\\nv\\u00e9\\r\\nv\\u00f4tre\\r\\nv\\u00f4tres\\r\\nw\\r\\nx\\r\\ny\\r\\nz\\r\\nzut\\r\\n\\u00e0\\r\\n\\u00e2\\r\\n\\u00e7a\\r\\n\\u00e8s\\r\\n\\u00e9taient\\r\\n\\u00e9tais\\r\\n\\u00e9tait\\r\\n\\u00e9tant\\r\\n\\u00e9tat\\r\\n\\u00e9tiez\\r\\n\\u00e9tions\\r\\n\\u00e9t\\u00e9\\r\\n\\u00e9t\\u00e9e\\r\\n\\u00e9t\\u00e9es\\r\\n\\u00e9t\\u00e9s\\r\\n\\u00eates\\r\\n\\u00eatre\\r\\n\\u00f4\\r\\n\\u0027ll\\r\\n\\u0027tis\\r\\n\\u0027twas\\r\\n\\u0027ve\\r\\n10\\r\\n39\\r\\na\\r\\na\\u0027s\\r\\nable\\r\\nableabout\\r\\nabout\\r\\nabove\\r\\nabroad\\r\\nabst\\r\\naccordance\\r\\naccording\\r\\naccordingly\\r\\nacross\\r\\nact\\r\\nactually\\r\\nad\\r\\nadded\\r\\nadj\\r\\nadopted\\r\\nae\\r\\naf\\r\\naffected\\r\\naffecting\\r\\naffects\\r\\nafter\\r\\nafterwards\\r\\nag\\r\\nagain\\r\\nagainst\\r\\nago\\r\\nah\\r\\nahead\\r\\nai\\r\\nain\\u0027t\\r\\naint\\r\\nal\\r\\nall\\r\\nallow\\r\\nallows\\r\\nalmost\\r\\nalone\\r\\nalong\\r\\nalongside\\r\\nalready\\r\\nalso\\r\\nalthough\\r\\nalways\\r\\nam\\r\\namid\\r\\namidst\\r\\namong\\r\\namongst\\r\\namoungst\\r\\namount\\r\\nan\\r\\nand\\r\\nannounce\\r\\nanother\\r\\nany\\r\\nanybody\\r\\nanyhow\\r\\nanymore\\r\\nanyone\\r\\nanything\\r\\nanyway\\r\\nanyways\\r\\nanywhere\\r\\nao\\r\\napart\\r\\napparently\\r\\nappear\\r\\nappreciate\\r\\nappropriate\\r\\napproximately\\r\\naq\\r\\nar\\r\\nare\\r\\narea\\r\\nareas\\r\\naren\\r\\naren\\u0027t\\r\\narent\\r\\narise\\r\\naround\\r\\narpa\\r\\nas\\r\\naside\\r\\nask\\r\\nasked\\r\\nasking\\r\\nasks\\r\\nassociated\\r\\nat\\r\\nau\\r\\nauth\\r\\navailable\\r\\naw\\r\\naway\\r\\nawfully\\r\\naz\\r\\nb\\r\\nba\\r\\nback\\r\\nbacked\\r\\nbacking\\r\\nbacks\\r\\nbackward\\r\\nbackwards\\r\\nbb\\r\\nbd\\r\\nbe\\r\\nbecame\\r\\nbecause\\r\\nbecome\\r\\nbecomes\\r\\nbecoming\\r\\nbeen\\r\\nbefore\\r\\nbeforehand\\r\\nbegan\\r\\nbegin\\r\\nbeginning\\r\\nbeginnings\\r\\nbegins\\r\\nbehind\\r\\nbeing\\r\\nbeings\\r\\nbelieve\\r\\nbelow\\r\\nbeside\\r\\nbesides\\r\\nbest\\r\\nbetter\\r\\nbetween\\r\\nbeyond\\r\\nbf\\r\\nbg\\r\\nbh\\r\\nbi\\r\\nbig\\r\\nbill\\r\\nbillion\\r\\nbiol\\r\\nbj\\r\\nbm\\r\\nbn\\r\\nbo\\r\\nboth\\r\\nbottom\\r\\nbr\\r\\nbrief\\r\\nbriefly\\r\\nbs\\r\\nbt\\r\\nbut\\r\\nbuy\\r\\nbv\\r\\nbw\\r\\nby\\r\\nbz\\r\\nc\\r\\nc\\u0027mon\\r\\nc\\u0027s\\r\\nca\\r\\ncall\\r\\ncame\\r\\ncan\\r\\ncan\\u0027t\\r\\ncannot\\r\\ncant\\r\\ncaption\\r\\ncase\\r\\ncases\\r\\ncause\\r\\ncauses\\r\\ncc\\r\\ncd\\r\\ncertain\\r\\ncertainly\\r\\ncf\\r\\ncg\\r\\nch\\r\\nchanges\\r\\nci\\r\\nck\\r\\ncl\\r\\nclear\\r\\nclearly\\r\\nclick\\r\\ncm\\r\\ncmon\\r\\ncn\\r\\nco\\r\\nco.\\r\\ncom\\r\\ncome\\r\\ncomes\\r\\ncomputer\\r\\ncon\\r\\nconcerning\\r\\nconsequently\\r\\nconsider\\r\\nconsidering\\r\\ncontain\\r\\ncontaining\\r\\ncontains\\r\\ncopy\\r\\ncorresponding\\r\\ncould\\r\\ncould\\u0027ve\\r\\ncouldn\\r\\ncouldn\\u0027t\\r\\ncouldnt\\r\\ncourse\\r\\ncr\\r\\ncry\\r\\ncs\\r\\ncu\\r\\ncurrently\\r\\ncv\\r\\ncx\\r\\ncy\\r\\ncz\\r\\nd\\r\\ndare\\r\\ndaren\\u0027t\\r\\ndarent\\r\\ndate\\r\\nde\\r\\ndear\\r\\ndefinitely\\r\\ndescribe\\r\\ndescribed\\r\\ndespite\\r\\ndetail\\r\\ndid\\r\\ndidn\\r\\ndidn\\u0027t\\r\\ndidnt\\r\\ndiffer\\r\\ndifferent\\r\\ndifferently\\r\\ndirectly\\r\\ndj\\r\\ndk\\r\\ndm\\r\\ndo\\r\\ndoes\\r\\ndoesn\\r\\ndoesn\\u0027t\\r\\ndoesnt\\r\\ndoing\\r\\ndon\\r\\ndon\\u0027t\\r\\ndone\\r\\ndont\\r\\ndoubtful\\r\\ndown\\r\\ndowned\\r\\ndowning\\r\\ndowns\\r\\ndownwards\\r\\ndue\\r\\nduring\\r\\ndz\\r\\ne\\r\\neach\\r\\nearly\\r\\nec\\r\\ned\\r\\nedu\\r\\nee\\r\\neffect\\r\\neg\\r\\neh\\r\\neight\\r\\neighty\\r\\neither\\r\\neleven\\r\\nelse\\r\\nelsewhere\\r\\nempty\\r\\nend\\r\\nended\\r\\nending\\r\\nends\\r\\nenough\\r\\nentirely\\r\\ner\\r\\nes\\r\\nespecially\\r\\net\\r\\net-al\\r\\netc\\r\\neven\\r\\nevenly\\r\\never\\r\\nevermore\\r\\nevery\\r\\neverybody\\r\\neveryone\\r\\neverything\\r\\neverywhere\\r\\nex\\r\\nexactly\\r\\nexample\\r\\nexcept\\r\\nf\\r\\nface\\r\\nfaces\\r\\nfact\\r\\nfacts\\r\\nfairly\\r\\nfar\\r\\nfarther\\r\\nfelt\\r\\nfew\\r\\nfewer\\r\\nff\\r\\nfi\\r\\nfifteen\\r\\nfifth\\r\\nfifty\\r\\nfify\\r\\nfill\\r\\nfind\\r\\nfinds\\r\\nfire\\r\\nfirst\\r\\nfive\\r\\nfix\\r\\nfj\\r\\nfk\\r\\nfm\\r\\nfo\\r\\nfollowed\\r\\nfollowing\\r\\nfollows\\r\\nfor\\r\\nforever\\r\\nformer\\r\\nformerly\\r\\nforth\\r\\nforty\\r\\nforward\\r\\nfound\\r\\nfour\\r\\nfr\\r\\nfree\\r\\nfrom\\r\\nfront\\r\\nfull\\r\\nfully\\r\\nfurther\\r\\nfurthered\\r\\nfurthering\\r\\nfurthermore\\r\\nfurthers\\r\\nfx\\r\\ng\\r\\nga\\r\\ngave\\r\\ngb\\r\\ngd\\r\\nge\\r\\ngeneral\\r\\ngenerally\\r\\nget\\r\\ngets\\r\\ngetting\\r\\ngf\\r\\ngg\\r\\ngh\\r\\ngi\\r\\ngive\\r\\ngiven\\r\\ngives\\r\\ngiving\\r\\ngl\\r\\ngm\\r\\ngmt\\r\\ngn\\r\\ngo\\r\\ngoes\\r\\ngoing\\r\\ngone\\r\\ngood\\r\\ngoods\\r\\ngot\\r\\ngotten\\r\\ngov\\r\\ngp\\r\\ngq\\r\\ngr\\r\\ngreat\\r\\ngreater\\r\\ngreatest\\r\\ngreetings\\r\\ngroup\\r\\ngrouped\\r\\ngrouping\\r\\ngroups\\r\\ngs\\r\\ngt\\r\\ngu\\r\\ngw\\r\\ngy\\r\\nh\\r\\nhad\\r\\nhadn\\u0027t\\r\\nhadnt\\r\\nhalf\\r\\nhappens\\r\\nhardly\\r\\nhas\\r\\nhasn\\r\\nhasn\\u0027t\\r\\nhasnt\\r\\nhave\\r\\nhaven\\r\\nhaven\\u0027t\\r\\nhavent\\r\\nhaving\\r\\nhe\\r\\nhe\\u0027d\\r\\nhe\\u0027ll\\r\\nhe\\u0027s\\r\\nhed\\r\\nhell\\r\\nhello\\r\\nhelp\\r\\nhence\\r\\nher\\r\\nhere\\r\\nhere\\u0027s\\r\\nhereafter\\r\\nhereby\\r\\nherein\\r\\nheres\\r\\nhereupon\\r\\nhers\\r\\nherself\\r\\nherse\\u201d\\r\\nhes\\r\\nhi\\r\\nhid\\r\\nhigh\\r\\nhigher\\r\\nhighest\\r\\nhim\\r\\nhimself\\r\\nhimse\\u201d\\r\\nhis\\r\\nhither\\r\\nhk\\r\\nhm\\r\\nhn\\r\\nhome\\r\\nhomepage\\r\\nhopefully\\r\\nhow\\r\\nhow\\u0027d\\r\\nhow\\u0027ll\\r\\nhow\\u0027s\\r\\nhowbeit\\r\\nhowever\\r\\nhr\\r\\nht\\r\\nhtm\\r\\nhtml\\r\\nhttp\\r\\nhu\\r\\nhundred\\r\\ni\\r\\ni\\u0027d\\r\\ni\\u0027ll\\r\\ni\\u0027m\\r\\ni\\u0027ve\\r\\ni.e.\\r\\nid\\r\\nie\\r\\nif\\r\\nignored\\r\\nii\\r\\nil\\r\\nill\\r\\nim\\r\\nimmediate\\r\\nimmediately\\r\\nimportance\\r\\nimportant\\r\\nin\\r\\ninasmuch\\r\\ninc\\r\\ninc.\\r\\nindeed\\r\\nindex\\r\\nindicate\\r\\nindicated\\r\\nindicates\\r\\ninformation\\r\\ninner\\r\\ninside\\r\\ninsofar\\r\\ninstead\\r\\nint\\r\\ninterest\\r\\ninterested\\r\\ninteresting\\r\\ninterests\\r\\ninto\\r\\ninvention\\r\\ninward\\r\\nio\\r\\niq\\r\\nir\\r\\nis\\r\\nisn\\r\\nisn\\u0027t\\r\\nisnt\\r\\nit\\r\\nit\\u0027d\\r\\nit\\u0027ll\\r\\nit\\u0027s\\r\\nitd\\r\\nitll\\r\\nits\\r\\nitself\\r\\nitse\\u201d\\r\\nive\\r\\nj\\r\\nje\\r\\njm\\r\\njo\\r\\njoin\\r\\njp\\r\\njust\\r\\nk\\r\\nke\\r\\nkeep\\r\\nkeeps\\r\\nkept\\r\\nkeys\\r\\nkg\\r\\nkh\\r\\nki\\r\\nkind\\r\\nkm\\r\\nkn\\r\\nknew\\r\\nknow\\r\\nknown\\r\\nknows\\r\\nkp\\r\\nkr\\r\\nkw\\r\\nky\\r\\nkz\\r\\nl\\r\\nla\\r\\nlarge\\r\\nlargely\\r\\nlast\\r\\nlately\\r\\nlater\\r\\nlatest\\r\\nlatter\\r\\nlatterly\\r\\nlb\\r\\nlc\\r\\nleast\\r\\nlength\\r\\nless\\r\\nlest\\r\\nlet\\r\\nlet\\u0027s\\r\\nlets\\r\\nli\\r\\nlike\\r\\nliked\\r\\nlikely\\r\\nlikewise\\r\\nline\\r\\nlittle\\r\\nlk\\r\\nll\\r\\nlong\\r\\nlonger\\r\\nlongest\\r\\nlook\\r\\nlooking\\r\\nlooks\\r\\nlow\\r\\nlower\\r\\nlr\\r\\nls\\r\\nlt\\r\\nltd\\r\\nlu\\r\\nlv\\r\\nly\\r\\nm\\r\\nma\\r\\nmade\\r\\nmainly\\r\\nmake\\r\\nmakes\\r\\nmaking\\r\\nman\\r\\nmany\\r\\nmay\\r\\nmaybe\\r\\nmayn\\u0027t\\r\\nmaynt\\r\\nmc\\r\\nmd\\r\\nme\\r\\nmean\\r\\nmeans\\r\\nmeantime\\r\\nmeanwhile\\r\\nmember\\r\\nmembers\\r\\nmen\\r\\nmerely\\r\\nmg\\r\\nmh\\r\\nmicrosoft\\r\\nmight\\r\\nmight\\u0027ve\\r\\nmightn\\u0027t\\r\\nmightnt\\r\\nmil\\r\\nmill\\r\\nmillion\\r\\nmine\\r\\nminus\\r\\nmiss\\r\\nmk\\r\\nml\\r\\nmm\\r\\nmn\\r\\nmo\\r\\nmore\\r\\nmoreover\\r\\nmost\\r\\nmostly\\r\\nmove\\r\\nmp\\r\\nmq\\r\\nmr\\r\\nmrs\\r\\nms\\r\\nmsie\\r\\nmt\\r\\nmu\\r\\nmuch\\r\\nmug\\r\\nmust\\r\\nmust\\u0027ve\\r\\nmustn\\u0027t\\r\\nmustnt\\r\\nmv\\r\\nmw\\r\\nmx\\r\\nmy\\r\\nmyself\\r\\nmyse\\u201d\\r\\nmz\\r\\nn\\r\\nna\\r\\nname\\r\\nnamely\\r\\nnay\\r\\nnc\\r\\nnd\\r\\nne\\r\\nnear\\r\\nnearly\\r\\nnecessarily\\r\\nnecessary\\r\\nneed\\r\\nneeded\\r\\nneeding\\r\\nneedn\\u0027t\\r\\nneednt\\r\\nneeds\\r\\nneither\\r\\nnet\\r\\nnetscape\\r\\nnever\\r\\nneverf\\r\\nneverless\\r\\nnevertheless\\r\\nnew\\r\\nnewer\\r\\nnewest\\r\\nnext\\r\\nnf\\r\\nng\\r\\nni\\r\\nnine\\r\\nninety\\r\\nnl\\r\\nno\\r\\nno-one\\r\\nnobody\\r\\nnon\\r\\nnone\\r\\nnonetheless\\r\\nnoone\\r\\nnor\\r\\nnormally\\r\\nnos\\r\\nnot\\r\\nnoted\\r\\nnothing\\r\\nnotwithstanding\\r\\nnovel\\r\\nnow\\r\\nnowhere\\r\\nnp\\r\\nnr\\r\\nnu\\r\\nnull\\r\\nnumber\\r\\nnumbers\\r\\nnz\\r\\no\\r\\nobtain\\r\\nobtained\\r\\nobviously\\r\\nof\\r\\noff\\r\\noften\\r\\noh\\r\\nok\\r\\nokay\\r\\nold\\r\\nolder\\r\\noldest\\r\\nom\\r\\nomitted\\r\\non\\r\\nonce\\r\\none\\r\\none\\u0027s\\r\\nones\\r\\nonly\\r\\nonto\\r\\nopen\\r\\nopened\\r\\nopening\\r\\nopens\\r\\nopposite\\r\\nor\\r\\nord\\r\\norder\\r\\nordered\\r\\nordering\\r\\norders\\r\\norg\\r\\nother\\r\\nothers\\r\\notherwise\\r\\nought\\r\\noughtn\\u0027t\\r\\noughtnt\\r\\nour\\r\\nours\\r\\nourselves\\r\\nout\\r\\noutside\\r\\nover\\r\\noverall\\r\\nowing\\r\\nown\\r\\np\\r\\npa\\r\\npage\\r\\npages\\r\\npart\\r\\nparted\\r\\nparticular\\r\\nparticularly\\r\\nparting\\r\\nparts\\r\\npast\\r\\npe\\r\\nper\\r\\nperhaps\\r\\npf\\r\\npg\\r\\nph\\r\\npk\\r\\npl\\r\\nplace\\r\\nplaced\\r\\nplaces\\r\\nplease\\r\\nplus\\r\\npm\\r\\npmid\\r\\npn\\r\\npoint\\r\\npointed\\r\\npointing\\r\\npoints\\r\\npoorly\\r\\npossible\\r\\npossibly\\r\\npotentially\\r\\npp\\r\\npr\\r\\npredominantly\\r\\npresent\\r\\npresented\\r\\npresenting\\r\\npresents\\r\\npresumably\\r\\npreviously\\r\\nprimarily\\r\\nprobably\\r\\nproblem\\r\\nproblems\\r\\npromptly\\r\\nproud\\r\\nprovided\\r\\nprovides\\r\\npt\\r\\nput\\r\\nputs\\r\\npw\\r\\npy\\r\\nq\\r\\nqa\\r\\nque\\r\\nquickly\\r\\nquite\\r\\nqv\\r\\nr\\r\\nran\\r\\nrather\\r\\nrd\\r\\nre\\r\\nreadily\\r\\nreally\\r\\nreasonably\\r\\nrecent\\r\\nrecently\\r\\nref\\r\\nrefs\\r\\nregarding\\r\\nregardless\\r\\nregards\\r\\nrelated\\r\\nrelatively\\r\\nresearch\\r\\nreserved\\r\\nrespectively\\r\\nresulted\\r\\nresulting\\r\\nresults\\r\\nright\\r\\nring\\r\\nro\\r\\nroom\\r\\nrooms\\r\\nround\\r\\nru\\r\\nrun\\r\\nrw\\r\\ns\\r\\nsa\\r\\nsaid\\r\\nsame\\r\\nsaw\\r\\nsay\\r\\nsaying\\r\\nsays\\r\\nsb\\r\\nsc\\r\\nsd\\r\\nse\\r\\nsec\\r\\nsecond\\r\\nsecondly\\r\\nseconds\\r\\nsection\\r\\nsee\\r\\nseeing\\r\\nseem\\r\\nseemed\\r\\nseeming\\r\\nseems\\r\\nseen\\r\\nsees\\r\\nself\\r\\nselves\\r\\nsensible\\r\\nsent\\r\\nserious\\r\\nseriously\\r\\nseven\\r\\nseventy\\r\\nseveral\\r\\nsg\\r\\nsh\\r\\nshall\\r\\nshan\\u0027t\\r\\nshant\\r\\nshe\\r\\nshe\\u0027d\\r\\nshe\\u0027ll\\r\\nshe\\u0027s\\r\\nshed\\r\\nshell\\r\\nshes\\r\\nshould\\r\\nshould\\u0027ve\\r\\nshouldn\\r\\nshouldn\\u0027t\\r\\nshouldnt\\r\\nshow\\r\\nshowed\\r\\nshowing\\r\\nshown\\r\\nshowns\\r\\nshows\\r\\nsi\\r\\nside\\r\\nsides\\r\\nsignificant\\r\\nsignificantly\\r\\nsimilar\\r\\nsimilarly\\r\\nsince\\r\\nsincere\\r\\nsite\\r\\nsix\\r\\nsixty\\r\\nsj\\r\\nsk\\r\\nsl\\r\\nslightly\\r\\nsm\\r\\nsmall\\r\\nsmaller\\r\\nsmallest\\r\\nsn\\r\\nso\\r\\nsome\\r\\nsomebody\\r\\nsomeday\\r\\nsomehow\\r\\nsomeone\\r\\nsomethan\\r\\nsomething\\r\\nsometime\\r\\nsometimes\\r\\nsomewhat\\r\\nsomewhere\\r\\nsoon\\r\\nsorry\\r\\nspecifically\\r\\nspecified\\r\\nspecify\\r\\nspecifying\\r\\nsr\\r\\nst\\r\\nstate\\r\\nstates\\r\\nstill\\r\\nstop\\r\\nstrongly\\r\\nsu\\r\\nsub\\r\\nsubstantially\\r\\nsuccessfully\\r\\nsuch\\r\\nsufficiently\\r\\nsuggest\\r\\nsup\\r\\nsure\\r\\nsv\\r\\nsy\\r\\nsystem\\r\\nsz\\r\\nt\\r\\nt\\u0027s\\r\\ntake\\r\\ntaken\\r\\ntaking\\r\\ntc\\r\\ntd\\r\\ntell\\r\\nten\\r\\ntends\\r\\ntest\\r\\ntext\\r\\ntf\\r\\ntg\\r\\nth\\r\\nthan\\r\\nthank\\r\\nthanks\\r\\nthanx\\r\\nthat\\r\\nthat\\u0027ll\\r\\nthat\\u0027s\\r\\nthat\\u0027ve\\r\\nthatll\\r\\nthats\\r\\nthatve\\r\\nthe\\r\\ntheir\\r\\ntheirs\\r\\nthem\\r\\nthemselves\\r\\nthen\\r\\nthence\\r\\nthere\\r\\nthere\\u0027d\\r\\nthere\\u0027ll\\r\\nthere\\u0027re\\r\\nthere\\u0027s\\r\\nthere\\u0027ve\\r\\nthereafter\\r\\nthereby\\r\\nthered\\r\\ntherefore\\r\\ntherein\\r\\ntherell\\r\\nthereof\\r\\ntherere\\r\\ntheres\\r\\nthereto\\r\\nthereupon\\r\\nthereve\\r\\nthese\\r\\nthey\\r\\nthey\\u0027d\\r\\nthey\\u0027ll\\r\\nthey\\u0027re\\r\\nthey\\u0027ve\\r\\ntheyd\\r\\ntheyll\\r\\ntheyre\\r\\ntheyve\\r\\nthick\\r\\nthin\\r\\nthing\\r\\nthings\\r\\nthink\\r\\nthinks\\r\\nthird\\r\\nthirty\\r\\nthis\\r\\nthorough\\r\\nthoroughly\\r\\nthose\\r\\nthou\\r\\nthough\\r\\nthoughh\\r\\nthought\\r\\nthoughts\\r\\nthousand\\r\\nthree\\r\\nthroug\\r\\nthrough\\r\\nthroughout\\r\\nthru\\r\\nthus\\r\\ntil\\r\\ntill\\r\\ntip\\r\\ntis\\r\\ntj\\r\\ntk\\r\\ntm\\r\\ntn\\r\\nto\\r\\ntoday\\r\\ntogether\\r\\ntoo\\r\\ntook\\r\\ntop\\r\\ntoward\\r\\ntowards\\r\\ntp\\r\\ntr\\r\\ntried\\r\\ntries\\r\\ntrillion\\r\\ntruly\\r\\ntry\\r\\ntrying\\r\\nts\\r\\ntt\\r\\nturn\\r\\nturned\\r\\nturning\\r\\nturns\\r\\ntv\\r\\ntw\\r\\ntwas\\r\\ntwelve\\r\\ntwenty\\r\\ntwice\\r\\ntwo\\r\\ntz\\r\\nu\\r\\nua\\r\\nug\\r\\nuk\\r\\num\\r\\nun\\r\\nunder\\r\\nunderneath\\r\\nundoing\\r\\nunfortunately\\r\\nunless\\r\\nunlike\\r\\nunlikely\\r\\nuntil\\r\\nunto\\r\\nup\\r\\nupon\\r\\nups\\r\\nupwards\\r\\nus\\r\\nuse\\r\\nused\\r\\nuseful\\r\\nusefully\\r\\nusefulness\\r\\nuses\\r\\nusing\\r\\nusually\\r\\nuucp\\r\\nuy\\r\\nuz\\r\\nv\\r\\nva\\r\\nvalue\\r\\nvarious\\r\\nvc\\r\\nve\\r\\nversus\\r\\nvery\\r\\nvg\\r\\nvi\\r\\nvia\\r\\nviz\\r\\nvn\\r\\nvol\\r\\nvols\\r\\nvs\\r\\nvu\\r\\nw\\r\\nwant\\r\\nwanted\\r\\nwanting\\r\\nwants\\r\\nwas\\r\\nwasn\\r\\nwasn\\u0027t\\r\\nwasnt\\r\\nway\\r\\nways\\r\\nwe\\r\\nwe\\u0027d\\r\\nwe\\u0027ll\\r\\nwe\\u0027re\\r\\nwe\\u0027ve\\r\\nweb\\r\\nwebpage\\r\\nwebsite\\r\\nwed\\r\\nwelcome\\r\\nwell\\r\\nwells\\r\\nwent\\r\\nwere\\r\\nweren\\r\\nweren\\u0027t\\r\\nwerent\\r\\nweve\\r\\nwf\\r\\nwhat\\r\\nwhat\\u0027d\\r\\nwhat\\u0027ll\\r\\nwhat\\u0027s\\r\\nwhat\\u0027ve\\r\\nwhatever\\r\\nwhatll\\r\\nwhats\\r\\nwhatve\\r\\nwhen\\r\\nwhen\\u0027d\\r\\nwhen\\u0027ll\\r\\nwhen\\u0027s\\r\\nwhence\\r\\nwhenever\\r\\nwhere\\r\\nwhere\\u0027d\\r\\nwhere\\u0027ll\\r\\nwhere\\u0027s\\r\\nwhereafter\\r\\nwhereas\\r\\nwhereby\\r\\nwherein\\r\\nwheres\\r\\nwhereupon\\r\\nwherever\\r\\nwhether\\r\\nwhich\\r\\nwhichever\\r\\nwhile\\r\\nwhilst\\r\\nwhim\\r\\nwhither\\r\\nwho\\r\\nwho\\u0027d\\r\\nwho\\u0027ll\\r\\nwho\\u0027s\\r\\nwhod\\r\\nwhoever\\r\\nwhole\\r\\nwholl\\r\\nwhom\\r\\nwhomever\\r\\nwhos\\r\\nwhose\\r\\nwhy\\r\\nwhy\\u0027d\\r\\nwhy\\u0027ll\\r\\nwhy\\u0027s\\r\\nwidely\\r\\nwidth\\r\\nwill\\r\\nwilling\\r\\nwish\\r\\nwith\\r\\nwithin\\r\\nwithout\\r\\nwon\\r\\nwon\\u0027t\\r\\nwonder\\r\\nwont\\r\\nwords\\r\\nwork\\r\\nworked\\r\\nworking\\r\\nworks\\r\\nworld\\r\\nwould\\r\\nwould\\u0027ve\\r\\nwouldn\\r\\nwouldn\\u0027t\\r\\nwouldnt\\r\\nws\\r\\nwww\\r\\nx\\r\\ny\\r\\nye\\r\\nyear\\r\\nyears\\r\\nyes\\r\\nyet\\r\\nyou\\r\\nyou\\u0027d\\r\\nyou\\u0027ll\\r\\nyou\\u0027re\\r\\nyou\\u0027ve\\r\\nyoud\\r\\nyoull\\r\\nyoung\\r\\nyounger\\r\\nyoungest\\r\\nyour\\r\\nyoure\\r\\nyours\\r\\nyourself\\r\\nyourselves\\r\\nyouve\\r\\nyt\\r\\nyu\\r\\nz\\r\\nza\\r\\nzero\\r\\nzm\\r\\nzr"
          }
        },
        "search_api_highlighting" : {
          "status" : 1,
          "weight" : "35",
          "settings" : {
            "prefix" : "\\u003Cstrong\\u003E",
            "suffix" : "\\u003C\\/strong\\u003E",
            "excerpt" : 1,
            "excerpt_length" : "500",
            "exclude_fields" : {
              "attachments_paddle_scald_file" : "attachments_paddle_scald_file",
              "search_api_aggregation_1" : "search_api_aggregation_1",
              "search_api_aggregation_2" : "search_api_aggregation_2",
              "search_api_aggregation_3" : "search_api_aggregation_3",
              "search_api_aggregation_4" : "search_api_aggregation_4",
              "search_api_panelizer_content" : "search_api_panelizer_content"
            },
            "highlight" : "always"
          }
        }
      },
      "fields" : {
        "attachments_paddle_scald_file" : { "type" : "text" },
        "author" : { "type" : "integer", "entity_type" : "user" },
        "body:summary" : { "type" : "text", "boost" : "2.0" },
        "body:value" : { "type" : "text" },
        "changed" : { "type" : "date" },
        "created" : { "type" : "date" },
        "field_paddle_cirro_action_strats" : { "type" : "list\\u003Cinteger\\u003E", "entity_type" : "taxonomy_term" },
        "field_paddle_cirro_methodology" : { "type" : "boolean" },
        "field_paddle_cirro_policy_themes" : { "type" : "list\\u003Cinteger\\u003E", "entity_type" : "taxonomy_term" },
        "field_paddle_cirro_settings" : { "type" : "integer", "entity_type" : "taxonomy_term" },
        "field_paddle_ebl_pub_date" : { "type" : "string" },
        "field_paddle_general_tags" : { "type" : "list\\u003Cinteger\\u003E", "entity_type" : "taxonomy_term" },
        "field_paddle_general_tags:name" : { "type" : "list\\u003Cstring\\u003E" },
        "field_paddle_kce_authors" : { "type" : "list\\u003Cinteger\\u003E", "entity_type" : "taxonomy_term" },
        "field_paddle_kce_keywords" : { "type" : "list\\u003Cinteger\\u003E", "entity_type" : "taxonomy_term" },
        "field_paddle_kce_publication_y" : { "type" : "integer", "entity_type" : "taxonomy_term" },
        "field_paddle_tags:name" : { "type" : "list\\u003Cstring\\u003E" },
        "language" : { "type" : "string" },
        "publication_date" : { "type" : "string" },
        "search_api_access_node" : { "type" : "list\\u003Cstring\\u003E" },
        "search_api_aggregation_1" : { "type" : "text" },
        "search_api_aggregation_2" : { "type" : "text" },
        "search_api_aggregation_3" : { "type" : "text", "boost" : "0.8" },
        "search_api_aggregation_4" : { "type" : "text", "boost" : "5.0" },
        "search_api_language" : { "type" : "string" },
        "search_api_panelizer_content" : { "type" : "text" },
        "status" : { "type" : "boolean" },
        "title" : { "type" : "string", "boost" : "5.0" },
        "type" : { "type" : "string" }
      }
    },
    "enabled" : "1",
    "read_only" : "0"
  }');
  return $items;
}

/**
 * Implements hook_default_search_api_page().
 */
function paddle_search_default_search_api_page() {
  $items = array();
  $items['search'] = entity_import('search_api_page', '{
    "index_id" : "node_index",
    "path" : "search",
    "name" : "Search",
    "machine_name" : "search",
    "description" : "",
    "options" : {
      "mode" : "terms",
      "fields" : {
        "attachments_paddle_scald_file" : "attachments_paddle_scald_file",
        "search_api_aggregation_1" : "search_api_aggregation_1",
        "search_api_aggregation_3" : "search_api_aggregation_3",
        "search_api_aggregation_4" : "search_api_aggregation_4",
        "search_api_panelizer_content" : "search_api_panelizer_content"
      },
      "per_page" : "20",
      "result_page_search_form" : 1,
      "get_per_page" : 0,
      "view_mode" : "search_api_page_result",
      "language_filter" : { "current" : "current" }
    },
    "enabled" : "1"
  }');
  return $items;
}

/**
 * Implements hook_default_search_api_server().
 */
function paddle_search_default_search_api_server() {
  $items = array();

  $solr_location = variable_get('paddle_search_solr_url');

  $solr_location_parts = $solr_location ? parse_url($solr_location) : array();

  $enabled = $solr_location ? '1' : '0';

  $location_parts_defaults = array(
    'scheme' => 'http',
    'host' => '',
    'user' => '',
    'pass' => '',
    'path' => '/',
    'port' => '80',
  );

  $solr_location_parts += $location_parts_defaults;

  $solr_server = new stdClass();
  $solr_server->name = "Solr server";
  $solr_server->machine_name = 'solr_server';
  $solr_server->description = '';
  $solr_server->class = 'search_api_solr_service';
  $solr_server->options = array(
    "clean_ids" => 1,
    "scheme" => $solr_location_parts['scheme'],
    "host" => $solr_location_parts['host'],
    "port" => $solr_location_parts['port'],
    "path" => $solr_location_parts['path'],
    "http_user" => $solr_location_parts['user'],
    "http_pass" => $solr_location_parts['pass'],
    "excerpt" => 0,
    "retrieve_data" => 0,
    "highlight_data" => 0,
    "http_method" => "POST",
    "autocorrect_spell" => 1,
    "autocorrect_suggest_words" => 1,
  );
  $solr_server->enabled = $enabled;

  $items['solr_server'] = entity_import('search_api_server', json_encode($solr_server));

  return $items;
}

/**
 * Implements hook_default_search_api_sort().
 */
function paddle_search_default_search_api_sort() {
  $items = array();
  $items['node_index__publication_date'] = entity_import('search_api_sort', '{
    "index_id" : "node_index",
    "field" : "publication_date",
    "name" : "Publication date",
    "enabled" : "1",
    "weight" : "10",
    "identifier" : "node_index__publication_date",
    "default_sort" : "0",
    "default_sort_no_terms" : "0",
    "default_order" : "desc",
    "options" : { "field_name" : "Publication date" }
  }');
  $items['node_index__search_api_relevance'] = entity_import('search_api_sort', '{
    "index_id" : "node_index",
    "field" : "search_api_relevance",
    "name" : "Relevance",
    "enabled" : "1",
    "weight" : "0",
    "identifier" : "node_index__search_api_relevance",
    "default_sort" : "1",
    "default_sort_no_terms" : "1",
    "default_order" : "desc",
    "options" : { "field_name" : "search_api_relevance" }
  }');
  $items['node_index__title'] = entity_import('search_api_sort', '{
    "index_id" : "node_index",
    "field" : "title",
    "name" : "Title",
    "enabled" : "1",
    "weight" : "5",
    "identifier" : "node_index__title",
    "default_sort" : "0",
    "default_sort_no_terms" : "0",
    "default_order" : "asc",
    "options" : { "field_name" : "Title" }
  }');
  return $items;
}
