<?php

  /////////////////////////////////////////////////////////////////////////////////////
  // CONFIGURATION FILE FOR NEWPOST2MAIL
  //
  // newpost2mail will work without any changes to this file.
  // However you can alter some settings here to match your needs.
  //
  /////////////////////////////////////////////////////////////////////////////////////



  // CONFIGURING RECIPIENTS FOR ALL FORUMS:

  $n2m_MAILTO[] = "NAME-OF-MAILING-LIST-L@lists.aegee.org";


  // CONFIGURING ACTIONS ON WHICH A MAIL WILL BE SENT
  //
  // phpBB3 knows of four different actions regarding new posts:
  //
  // post : a new thread has been started
  // reply: a new post in a thread has been made
  // quote: same as reply but with quotes
  // edit : a post has been edited
  //
  // By default mails will be sent for all actions. To switch off one of them, set it to 0.
  // If you set all of them to 0 no mails will be sent!

  $n2m_MAIL_ON[post]  = 1;
  $n2m_MAIL_ON[reply] = 1;
  $n2m_MAIL_ON[quote] = 1;
  $n2m_MAIL_ON[edit]  = 0;



  // CONFIGURING SUBJECT LINE OF MAIL:
  //
  // You can configure the mail subject to whatever you like using the following
  // variables:
  //
  //  $post_USERNAME     = name of the posting user
  //  $post_IP           = IP of the posting user
  //  $post_HOST         = hostname for the above IP
  //  $post_SITENAME     = name of your site
  //  $post_TOPICTITLE   = title of the topic the post was made to
  //  $post_FORUMNAME    = name of the forum the post was made to
  //  $post_FORUMPARENTS = parent forums list of $post_FORUMNAME separeted by " / "
  //  $post_MODE         = type of post (post, reply, quote, edit ...)
  //  $post_SUBJECT      = subject of the post
  //  $post_EDITOR       = name of editing user (only if post was edited by 3rd party)
  //
  // The default setting would give you something like
  // [reply] Re: who can help?

  $n2m_SUBJECT = "$post_FORUMPARENTS$post_FORUMNAME / $post_SUBJECT";



  // CONFIGURE IF EMAIL ADDRESS OF USER WILL BE DISPLAYED:
  //
  // $n2m_ALWAYS_SHOW_EMAIL = 0;
  //
  // Only if the user has allowed to be contacted by email, the address including a
  // mailto: hyperlink is displayed behind the username
  //
  //
  // $n2m_ALWAYS_SHOW_EMAIL = 1;
  //
  // Same as above but always display the email address

  $n2m_ALWAYS_SHOW_EMAIL = 0;



  // CONFIGURE IF SIGNATURE OF USER IS DISPLAYED:
  //
  // If you do not want to see the signature in your mail use
  //
  // $n2m_SHOW_SIG = 0;

  $n2m_SHOW_SIG = 1;



  // TRANSLATIONS
  //
  // By default n2m reads your board configuration for its default language and uses that.
  // If you want to override your boards default language, you can set your own language:
  //
  // $n2m_LANG = "[your language]";
  //
  $n2m_LANG = "en";


  // english (en)    !!! DON'T DELETE - EVEN IF YOU DON'T USE ENGLISH !!!

  $n2m_TEXT[en][mode]         = "Mode";
  $n2m_TEXT[en][forum]        = "Forum";
  $n2m_TEXT[en][thread]       = "Thread";
  $n2m_TEXT[en][subject]      = "Subject";
  $n2m_TEXT[en][user]         = "User";
  $n2m_TEXT[en][ip_hostname]  = "IP/Host";
  $n2m_TEXT[en][host_na]      = "(n/a)";
  $n2m_TEXT[en][actions]      = "Actions";
  $n2m_TEXT[en][reply]        = "reply";
  $n2m_TEXT[en][quote]        = "quote";
  $n2m_TEXT[en][edit]         = "edit";
  $n2m_TEXT[en][delete]       = "delete";
  $n2m_TEXT[en][info]         = "info";
  $n2m_TEXT[en][pm]           = "pm";
  $n2m_TEXT[en][email]        = "email";
  $n2m_TEXT[en][attachments]  = "Attachments";
  $n2m_TEXT[en][edited_by]    = "edited by";
  $n2m_TEXT[en][edit_reason]  = "reason";

?>
