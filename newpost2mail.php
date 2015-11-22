<?php

  require_once('Mail/mimePart.php');

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
      $post_FORUMPARENTS_laquo .= $temp["0"]. " « ";
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
      $from = html_entity_decode($user->data['username']) . " <" .
       (($user->data['user_allow_viewemail'] or $n2m_ALWAYS_SHOW_EMAIL)
        ? $user->data['user_email'] : $user->data['username'] . "@aegee.org")  . ">";
      $headers .= "From: ". Mail_mimePart::encodeHeader("from", $from, "UTF-8") . "\n";
      $headers .= "X-Mailer: newpost2mail $version for phpBB3\n";
      $headers .= "MIME-Version: 1.0\n";
      $headers .= "Content-type: text/plain; charset=UTF-8; format=flowed\n";



      // build the email body

      $message = html_entity_decode(str_replace("<br />", "<br />\n",
        generate_text_for_edit($data[message], $data[bbcode_uid],
	                       $post_data[forum_desc_options])["text"])."\n\n");

      //convert BBCode to text/plain; format=flowed
      require_once 'JBBCode/Parser.php';

      class QuoteWithOption extends JBBCode\CodeDefinition {
        public function __construct() {
          parent::__construct();
          $this->setTagName("quote");
          $this->useOption = true;
        }
        public function asHtml(JBBCode\ElementNode $el) {
          $result = "\nQuote from " . $el->getAttribute()["quote"] . ":\n";
          foreach (preg_split ("/\R/", $this->getContent($el)) as $line)
            $result .= "> " . $line . "\n";
          return $result . "\n";
        }
      }

      class QuoteWithoutOption extends JBBCode\CodeDefinition {
        public function __construct() {
          parent::__construct();
          $this->setTagName("quote");
        }
        public function asHtml(JBBCode\ElementNode $el) {
          $result = "\n";
          foreach (preg_split ("/\R/", $this->getContent($el)) as $line)
            $result .= "> " . $line . "\n";
          return $result . "\n";
        }
      }

      $parser = new JBBCode\Parser();

      $builder = new JBBCode\CodeDefinitionBuilder('i', '/{param}/');
      $parser->addCodeDefinition($builder->build());
      $builder = new JBBCode\CodeDefinitionBuilder('u', '_{param}_');
      $parser->addCodeDefinition($builder->build());
      $builder = new JBBCode\CodeDefinitionBuilder('b', '*{param}*');
      $parser->addCodeDefinition($builder->build());
      $parser->addCodeDefinition(new QuoteWithOption());
      $parser->addCodeDefinition(new QuoteWithoutOption());
      $builder = new JBBCode\CodeDefinitionBuilder('code', '{param}');
      $parser->addCodeDefinition($builder->build());
      $builder = (new JBBCode\CodeDefinitionBuilder('color', '{param}'))->setUseOption(true);
      $parser->addCodeDefinition($builder->build());
      $builder = (new JBBCode\CodeDefinitionBuilder('size', '{param}'))->setUseOption(true);
      $parser->addCodeDefinition($builder->build());

      $parser->parse($message);
      $message = $parser->getAsHtml();
      // build the informational table

      $message .= "$phrase[subject]: $post_SUBJECT\n";
      $message .= "$phrase[thread]: $post_TOPICTITLE $thread_url\n";
      $message .= "$phrase[forum] : $post_FORUMPARENTS_laquo$post_FORUMNAME $forum_url\n";
      $message .= "$phrase[actions]:\n";
      $message .= "  Reply address in the AEGEE Forum: " . $reply_url . "\n";
      $message .= "  Post URL: " . $post_url . "\n";
      $message .= "  Info URL: " . $info_url . "\n\n";

      $message .= "--\nThis message is generated upon adding a new posting in the AEGEE Forum, https://www.aegee.org/forum . You can answer directly to the sender by clicking the reply button, if the sender has activated its @aegee.org address, and does not want to hide his/her identity, otherwise the sending address of this email does not exist.  Sending emails from the AEGEE forum is experimental.  Direct your feedback at forum@aegee.org .";

      if ($post_HOST == $post_IP) $post_HOST = $phrase[host_na];
      // build the post text table

      // search for inline attachments to show them in the post text

      if (!empty($data[attachment_data])) parse_attachments($data[forum_id], $data[message], $data[attachment_data], $dummy, true);



      // generate post text


      // show attachments if not already shown in the post text

      if (!empty($data[attachment_data])) {
        $message .= "$phrase[attachments]:\n";
        foreach ($data[attachment_data] as $filename) {
          $message .= "  " . print_r($filename, 1) . "\n";
        }
        $message .= "\n";
      }


      // add signature

      if ($n2m_SHOW_SIG) {
        if ($mode != "edit") {
          if ( ($user->data[user_sig]) and ($data[enable_sig]) ) {
            $message .= "\nSignature:\n  ";
            $message .= generate_text_for_edit($user->data[user_sig], $user->data[user_sig_bbcode_uid], $post_data[forum_desc_options])["text"] ."\n\n";
          }
        }
      }


      // encode subject

      $subject = mail_encode(html_entity_decode($n2m_SUBJECT));



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

      // make text "flow" in plain/text

      $temp = $message; $message = '';
      foreach (preg_split ("/\R/", $temp) as $line)
        $message .= wordwrap($line, 75, $line[0] == ">" ? " \r\n>" : " \r\n") . "\r\n";


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