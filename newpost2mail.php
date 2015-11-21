<?php


  // newpost2mail beta 21 for phpBB3 by Stefan Hendricks
  // See http://henmedia.de for latest version
  //
  // Do not wonder if this code is formatted strange
  // in your editor as I always use a tabsize of 2 :)


  // only allow access from "posting.php"

  // if (!is_array($_SERVER)) { // phpBB3 >= v3.1.x


  if (version_compare(PHPBB_VERSION, "3.1.0", ">=")) { // phpBB3 >= v3.1.0

    $my_script_name = $request->server("SCRIPT_NAME");
    $my_script_uri  = $request->server("SCRIPT_URI");

  } else { // phpBB3 < v3.1.0

    $my_script_name = $_SERVER["SCRIPT_NAME"];
    $my_script_uri  = $_SERVER["SCRIPT_URI"];

  }

  if ((substr($my_script_name, -11) != "posting.php") AND (substr($my_script_uri, -11) != "posting.php")) die("ACCESS DENIED");


  newpost2mail($data);

  function newpost2mail($data) {

    global $config, $mode, $user, $post_data, $phpEx, $phpbb_root_path, $db;

    $version = "beta 21";


    // variables that can be used in newpost2mail.config.php to build an individial subject line

    $post_SITENAME    = $config['sitename'];
    $post_FORUMNAME   = $data['forum_name'];
    $post_MODE        = $mode;
    $post_TOPICTITLE  = $data['topic_title'];
    $post_SUBJECT     = $post_data['post_subject'];
    $post_USERNAME    = $user->data['username'];
    $post_IP          = $data['poster_ip'];
    $post_HOST        = @gethostbyaddr($post_IP);



    // 3rd party edit?

    if ( ($mode == "edit" ) AND ($post_data[username] != $user->data['username']) ) {
      $post_EDITOR    = $user->data['username'];
      $post_USERNAME  = $post_data[username];
    }



    // get forum parents

    foreach (get_forum_parents($post_data) as $temp) {
      $post_FORUMPARENTS       .= $temp["0"]. " / ";
      $post_FORUMPARENTS_laquo .= $temp["0"]. " &laquo; ";
    }



    // read configuration

    include($phpbb_root_path . 'newpost2mail.config.php');



    // check if the actual mode is set for sending mails

    if ($n2m_MAIL_ON[$mode]) {

      // if there is a language set in newpost2mail.config.php then use that setting.
      // Otherwise read default language from board config and use that.

      $n2m_LANG ? $lang = $n2m_LANG : $lang = $config['default_lang'];




      // get (translated) phrases and convert them to UTF8

      foreach ($n2m_TEXT[en] as $key=>$value) {
        if ($n2m_TEXT[$lang][$key]) {
          $phrase[$key] = utf8_encode($n2m_TEXT[$lang][$key]);
        } else {
          $phrase[$key] = utf8_encode($n2m_TEXT[en][$key]);
        }
      }




      // set variables for later use

      $board_url      = generate_board_url();
      if (substr($board_url, -1) != "/") $board_url .= "/";

      $forum_url      = $board_url . "viewforum.php?f=$data[forum_id]";
      $thread_url     = $board_url . "viewtopic.php?f=$data[forum_id]&t=$data[topic_id]";
      $post_url       = $board_url . "viewtopic.php?f=$data[forum_id]&t=$data[topic_id]&p=$data[post_id]#p$data[post_id]";
      $u_profile_url  = $board_url . "memberlist.php?mode=viewprofile&u=$post_data[poster_id]";
      $e_profile_url  = $board_url . "memberlist.php?mode=viewprofile&u=$post_data[post_edit_user]";
      $reply_url      = $board_url . "posting.php?mode=reply&f=$data[forum_id]&t=$data[topic_id]";
      $edit_url       = $board_url . "posting.php?mode=edit&f=$data[forum_id]&p=$data[post_id]";
      $quote_url      = $board_url . "posting.php?mode=quote&f=$data[forum_id]&p=$data[post_id]";
      $delete_url     = $board_url . "posting.php?mode=delete&f=$data[forum_id]&p=$data[post_id]";
      $info_url       = $board_url . "mcp.php?i=main&mode=post_details&f=$data[forum_id]&p=$data[post_id]";
      $pm_url         = $board_url . "ucp.php?i=pm&mode=compose&action=quotepost&p=$data[post_id]";
      $email_url      = $board_url . "memberlist.php?mode=email&u=$post_data[poster_id]";



      // build the email header

      include_once($phpbb_root_path . 'includes/functions_messenger.' . $phpEx);

      $headers .= "Date: ".date("D, j M Y H:i:s O")."\n";
      $headers .= "From: \"".mail_encode(html_entity_decode($config[sitename]))."\" <$config[board_email]>\n";
      $headers .= "X-Mailer: newpost2mail $version for phpBB3\n";
      $headers .= "MIME-Version: 1.0\n";
      $headers .= "Content-type: text/html; charset=UTF-8\n";



      // build the email body

      $message .= "<HTML>\n";
      $message .= "<HEAD>\n";
      $message .= "<base href='$board_url'>\n";
      $message .= "<META http-equiv='content-type' content='text/html; charset=UTF-8'>\n";



      // insert style definitions from newpost2mail.css

      $message .= "<style type='text/css' media='screen'>\n";
      $message .= file_get_contents($phpbb_root_path."newpost2mail.css");
      $message .= "\n</style>\n";

      $message .= "</HEAD>\n";
      $message .= "<BODY>\n";



      // build the informational table

      $message .= "<table class='table_info'>\n";
      $message .= "<tr><td>$phrase[user]</td><td>: <a href='$u_profile_url'><b>$post_USERNAME</b></a>";
      if ($post_EDITOR) $message .= "&nbsp;&nbsp;-&raquo; $phrase[edited_by] <a href='$e_profile_url'><b>$post_EDITOR</b></a>";
      if (($user->data['user_allow_viewemail']) or ($n2m_ALWAYS_SHOW_EMAIL)) $message .= " (<a href='mailto:". $user->data['user_email'] . "'>". $user->data['user_email'] ."</a>)";
      $message .= "</td>\n</tr>\n";
      $message .= "<tr><td>$phrase[subject]</td><td>: <a href='$post_url'><b>$post_SUBJECT</b></a></td></tr>\n";
      $message .= "<tr><td>$phrase[thread]</td><td>: <a href='$thread_url'>$post_TOPICTITLE</a></td></tr>\n";
      $message .= "<tr><td>$phrase[forum]</td><td>: <a href='$forum_url'>$post_FORUMPARENTS_laquo$post_FORUMNAME</a></td></tr>\n";
      $message .= "<tr><td>$phrase[mode]</td><td>: $mode</td></tr>\n";

      if ($post_HOST == $post_IP) $post_HOST = $phrase[host_na];
      $message .= "<tr><td>$phrase[ip_hostname]</td><td>: $post_IP / $post_HOST</td></tr>\n";
      $message .= "<tr><td>$phrase[actions]</td><td>: [<a href='$reply_url'>$phrase[reply]</a>] [<a href='$quote_url'>$phrase[quote]</a>] [<a href='$edit_url'>$phrase[edit]</a>] [<a href='$delete_url'>$phrase[delete]</a>] [<a href='$info_url'>$phrase[info]</a>] [<a href='$pm_url'>$phrase[pm]</a>] [<a href='$email_url'>$phrase[email]</a>]</td></tr>\n";
      $message .= "</table>\n";



      // build the post text table

      $message .= "<table class='table_post' width='$n2m_WIDTH'>\n";
      $message .= "<tr><td><div class='content'>\n";



      // search for inline attachments to show them in the post text

      if (!empty($data[attachment_data])) parse_attachments($data[forum_id], $data[message], $data[attachment_data], $dummy, true);


      // generate post text

      $message .= str_replace("<br />", "<br />\n", generate_text_for_display($data[message], $data[bbcode_uid], $data[bbcode_bitfield], $post_data[forum_desc_options]))."\n";



      // show attachments if not already shown in the post text

      if (!empty($data[attachment_data])) {
        $message .= "<br />\n<dl class='attachbox'><dt>$phrase[attachments]:</dt><dd>\n";
        foreach ($data[attachment_data] as $filename) {
          $message .= print_r($filename, 1);
        }
        $message .= "</dl>\n";
      }


      // convert relative smily attachment to absolute url

      $message = str_replace("./download/file.php?id=", $board_url."download/file.php?id=", $message);


      // 3rd party edit

      if ($post_data[post_edit_reason]) {
        $post_EDITOR ? $edited_by = $post_EDITOR : $edited_by = $post_USERNAME;
        $message .= "<div class='notice'>-&raquo; $phrase[edited_by] $edited_by, $phrase[edit_reason]: <em>$post_data[post_edit_reason]</em></div>\n";
      }


      // add signature

      if ($n2m_SHOW_SIG) {
        if ($mode != "edit") {
          if ( ($user->data[user_sig]) and ($data[enable_sig]) ) {
            $message .= "<div class='signature'>";
            $message .= generate_text_for_display($user->data[user_sig], $user->data[user_sig_bbcode_uid], $user->data[user_sig_bbcode_bitfield], $post_data[forum_desc_options])."\n";
            $message .= "</div>\n";
          }
        } else {
          if ( ($post_data[user_sig]) and ($post_data[enable_sig]) and ($n2m_SHOW_SIG)) {
            $message .= "<div class='signature'>\n";
            $message .= generate_text_for_display($post_data[user_sig], $post_data[user_sig_bbcode_uid], $post_data[user_sig_bbcode_bitfield], $post_data[forum_desc_options])."\n";
            $message .= "</div>\n";
          }
        }
      }


      // convert relative smily url to absolute url

      $message = str_replace("./$config[smilies_path]", "$board_url$config[smilies_path]", $message);

      $message .= "</div></td></tr></table>\n";



      // ask for donation and build an own table for that :)

      $paypal_USD = "https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=stefan%40hendricks%2donline%2ede&item_name=newpost2mail%20for%20phpBB3&no_shipping=1&tax=0&currency_code=USD&bn=PP%2dDonationsBF&charset=UTF%2d8&lc=$lang";
      $paypal_EUR = "https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=stefan%40hendricks%2donline%2ede&item_name=newpost2mail%20for%20phpBB3&no_shipping=1&tax=0&currency_code=EUR&bn=PP%2dDonationsBF&charset=UTF%2d8&lc=$lang";

      $message .= "<table class='table_version' width='$n2m_WIDTH'>\n";
      $message .= "<tr><td align='center'>";

      if ($lang == "de") {
        $message .= utf8_encode("Diese E-Mail wurde von <i>newpost2mail $version</i> für phpBB3 versendet.<br >Dokumentationen &amp; Updates finden Sie unter <a href='http://henmedia.de'>http://henmedia.de</a>.<br /><br />");
        $message .= utf8_encode("Wenn Sie diese Erweiterung nützlich finden, dann können Sie deren<br />Entwicklung mit einer <a href='$paypal_EUR'>PayPal-Spende</a> unterstützen - vielen Dank :-)\n");
      } else {
        $message .= "This message was sent by <i>newpost2mail $version</i> for phpBB3.<br />Visit <a href='http://henmedia.de'>http://henmedia.de</a> for updates and documentation.<br /><br />";
        $message .= "If you find this MOD useful, you can support this project using PayPal.<br>To make a donation click here: <a href='$paypal_USD'>donate USD</a> / <a href='$paypal_EUR'>donate EUR</a> - thank you :-)\n";
      }

      $message .= "</td></tr></table>\n";

      // end donation



      $message .= "</BODY></HTML>\n";

      $message = wordwrap($message, 256);


      // encode subject

      $subject = mail_encode(html_entity_decode($n2m_SUBJECT));


      // fix for phpBB 3.05 !
      $subject = str_replace("\r", "", $subject);
      $subject = str_replace("\n", "", $subject);



      // send email to board contact address?

      if ($n2m_MAILTO_BOARDCONTACT) $n2m_MAILTO[] = $config[board_contact];



      // send email to group?

      foreach ($n2m_MAILTO_GROUP as $group) {
        $sql = $db->sql_build_query('SELECT', array('SELECT'  => 'u.user_email',
                                                    'FROM'    => array(USERS_TABLE => 'u', GROUPS_TABLE => 'g', USER_GROUP_TABLE => 'ug'),
                                                    'WHERE'   => 'ug.group_id = g.group_id AND ug.user_id = u.user_id AND u.user_email != \'\' AND lower(g.group_name) = \'' . strtolower($group) . '\''));
        $result = $db->sql_query($sql);
        while ($row = $db->sql_fetchrow($result)) $n2m_MAILTO[] = $row['user_email'];
        $db->sql_freeresult($result);
      }



      // add recipients by forum

      if (is_array($n2m_MONITOR_FORUM[$data[forum_id]])) $n2m_MAILTO = array_merge($n2m_MAILTO, $n2m_MONITOR_FORUM[$data[forum_id]]);



      // convert all addresses to lowercase and delete any empty addresses

      foreach ($n2m_MAILTO as $key => $value) {
        if (is_null($value) or ($value == "")) {
          unset($n2m_MAILTO[$key]);
        } else {
          $n2m_MAILTO[$key] = strtolower($n2m_MAILTO[$key]);
        }
      }



      // insure that every address is only used once

      $n2m_MAILTO = array_unique($n2m_MAILTO);


      // Testversion, Mails an Author des Artikels verhindern
      // unset($n2m_MAILTO[array_search($user->data['user_email'], $n2m_MAILTO)]);


      // die($message); // for debugging purposes, mail will be shown in browser and not sent out if we uncomment this line



      // and finally send the mails

      foreach ($n2m_MAILTO as $mailto) {
        if ($config['smtp_delivery']) { // SMTP?
          $tempto[to][email] = $mailto;
          $to[to] = $tempto;
          $result = smtpmail($to, $subject, str_replace("\n.", "\n..", $message), $err_msg, $headers);
          reset($to);
          reset($tempto);
        } else { // or PHP mail?
          $result = $config['email_function_name']($mailto, $subject, $message, $headers);
       }
      }
    }
  }

?>