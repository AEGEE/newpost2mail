<?php

  /////////////////////////////////////////////////////////////////////////////////////
  // CONFIGURATION FILE FOR NEWPOST2MAIL
  //
  // newpost2mail will work without any changes to this file.
  // However you can alter some settings here to match your needs.
  //
  /////////////////////////////////////////////////////////////////////////////////////



  // CONFIGURING RECIPIENTS FOR ALL FORUMS:
  //
  // Mails are only sent to the board contact address configured in your phpBB by default.
  //
  // To switch this off or it doesn't work for your setup use
  //
  // $n2m_MAILTO_BOARDCONTACT = 0;
  //
  // and be sure to set additional addresses (see below) or no mails will be sent at all.

  $n2m_MAILTO_BOARDCONTACT = 1;


  // You can configure any additional address(es) by changing the following line:
  //
  // $n2m_MAILTO[] = "";
  // to
  // $n2m_MAILTO[] = "your@address.here";
  //
  // If you want to send to multiple addresses, simply repeat this line for each
  // recipient:
  //
  // $n2m_MAILTO[] = "your@1st_address.here";
  // $n2m_MAILTO[] = "your@2nd_address.here";
  // $n2m_MAILTO[] = "your@3rd_address.here";

  $n2m_MAILTO[] = "";


  // You can send mails to groups defined in your phpBB3 installation by changing the
  // follwing line:
  //
  // $n2m_MAILTO_GROUP[] = "";
  // to
  // $n2m_MAILTO_GROUP[] = "your_group_here";
  //
  // Now newpost2mail will send an email to every user which is member of this group.
  //
  // It may be a good idea to create a group called "n2m_recipients" or similar in your
  // phpBB3 installation, so you can manage the recipients by just adding the users who
  // should receive emails to this group.
  //
  // If you want to send to multiple groups, simply repeat this line for each group:
  //
  // $n2m_MAILTO_GROUP[] = "your_1st_group_here";
  // $n2m_MAILTO_GROUP[] = "your_2nd_group_here";
  // $n2m_MAILTO_GROUP[] = "your_3rd_group_here";

  $n2m_MAILTO_GROUP[] = "";


  // CONFIGURING RECIPIENTS FOR INDIVIDUAL FORUMS:
  //
  // If you want to send mails to indivdual recipients depending on the forum, this
  // can be configured here. This may be useful if you do not want to monitor all
  // forums or if you have different moderators for your forums and all or some of them
  // should receive only mails for their forum(s).
  //
  // You need the numerical forum ID for that, which can be found in the URL of the
  // forum in question. Just open a forum and look at its URL. You'll get something
  // like http://your-forum.com/viewtopic.php?f=18&t=789
  // In the example above we have "f=18" which means the forum ID is 18
  //
  // Now we add $n2m_MONITOR_FORUM[ID][] = "any@address.here" for EACH forum and EACH
  // recipent. You cannot have multiple IDs or multiple recipients in one line!
  //
  // If you want to have mails for forum ID 3 to peter@yourdomain.com and
  // tom@yourdomain.com and mails for forum ID 7 to mary@yourdomain.com and to
  // tom@yourdomain.com, you'll have to change
  //
  // $n2m_MONITOR_FORUM[][] = "";
  //
  // to the following lines:
  //
  // $n2m_MONITOR_FORUM[3][] = "peter@yourdomain.com";
  // $n2m_MONITOR_FORUM[3][] = "tom@yourdomain.com";
  // $n2m_MONITOR_FORUM[7][] = "mary@yourdomain.com";
  // $n2m_MONITOR_FORUM[7][] = "tom@yourdomain.com";

  $n2m_MONITOR_FORUM[][] = "";



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
  $n2m_MAIL_ON[edit]  = 1;



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

  $n2m_SUBJECT = "[$post_MODE] $post_SUBJECT";



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

  $n2m_ALWAYS_SHOW_EMAIL = 1;



  // CONFIGURE IF SIGNATURE OF USER IS DISPLAYED:
  //
  // If you do not want to see the signature in your mail use
  //
  // $n2m_SHOW_SIG = 0;

  $n2m_SHOW_SIG = 1;



  // CONFIGURING BASIC LAYOUT OF MAIL:
  //
  // You can change the width of the post content table here:

  $n2m_WIDTH = 700;

  // More layout configuration can be made by editing newpost2mail.css




  // TRANSLATIONS
  //
  // By default n2m reads your board configuration for its default language and uses that.
  // If you want to override your boards default language, you can set your own language:
  //
  // $n2m_LANG = "[your language]";
  //
  // Examples for setting to english or german:
  //
  // $n2m_LANG = "en";
  // $n2m_LANG = "de";
  //
  // If there is no adequate translation present in this file, n2m will use english.

  $n2m_LANG = "";


  // The phrases for common langauges are defined below.
  // If you want to create your own translations, just copy the section with the english
  // phrases (this means all lines like $n2m_Text[en][...] = "...";) and replace [en] by
  // your language code and "..." with your translated phrases.
  // If you don't translate all phrases, english is used for all not translated phrases.


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


  // If you don't use languages other than english you can delete them from this file


  // german (de)

  $n2m_TEXT[de][mode]         = "Modus";
  $n2m_TEXT[de][forum]        = "Forum";
  $n2m_TEXT[de][thread]       = "Thema";
  $n2m_TEXT[de][subject]      = "Betreff";
  $n2m_TEXT[de][user]         = "Benutzer";
  $n2m_TEXT[de][ip_hostname]  = "IP/Host";
  $n2m_TEXT[de][host_na]      = "(n.v.)";
  $n2m_TEXT[de][actions]      = "Aktionen";
  $n2m_TEXT[de][reply]        = "antworten";
  $n2m_TEXT[de][quote]        = "zitieren";
  $n2m_TEXT[de][edit]         = "bearbeiten";
  $n2m_TEXT[de][delete]       = "löschen";
  $n2m_TEXT[de][info]         = "Informationen";
  $n2m_TEXT[de][pm]           = "PN";
  $n2m_TEXT[de][email]        = "E-Mail";
  $n2m_TEXT[de][attachments]  = "Dateianhänge";
  $n2m_TEXT[de][edited_by]    = "bearbeitet durch";
  $n2m_TEXT[de][edit_reason]  = "Grund";


  // spanish (es)

  $n2m_TEXT[es][mode]         = "Modo";
  $n2m_TEXT[es][forum]        = "Foro";
  $n2m_TEXT[es][thread]       = "Hilo";
  $n2m_TEXT[es][subject]      = "Asunto";
  $n2m_TEXT[es][user]         = "Usuario";
  $n2m_TEXT[es][ip_hostname]  = "IP/Host";
  $n2m_TEXT[es][host_na]      = "(n/a)";
  $n2m_TEXT[es][actions]      = "Acciones";
  $n2m_TEXT[es][reply]        = "respuesta";
  $n2m_TEXT[es][quote]        = "cita";
  $n2m_TEXT[es][edit]         = "editar";
  $n2m_TEXT[es][delete]       = "borrar";
  $n2m_TEXT[es][info]         = "info";
  $n2m_TEXT[es][pm]           = "mp";
  $n2m_TEXT[es][email]        = "email";
  $n2m_TEXT[es][attachments]  = "Adjuntos";
  $n2m_TEXT[es][edited_by]    = "editado por";
  $n2m_TEXT[es][edit_reason]  = "razón";


  // portuguese (pt)

  $n2m_TEXT[pt][mode]         = "Modo";
  $n2m_TEXT[pt][forum]        = "Fórum";
  $n2m_TEXT[pt][thread]       = "Tópico";
  $n2m_TEXT[pt][subject]      = "Assunto";
  $n2m_TEXT[pt][user]         = "Utilizador";
  $n2m_TEXT[pt][ip_hostname]  = "IP/Endereço";
  $n2m_TEXT[pt][host_na]      = "(n/d)";
  $n2m_TEXT[pt][actions]      = "Acções";
  $n2m_TEXT[pt][reply]        = "responder";
  $n2m_TEXT[pt][quote]        = "citar";
  $n2m_TEXT[pt][edit]         = "editar";
  $n2m_TEXT[pt][delete]       = "apagar";
  $n2m_TEXT[pt][info]         = "info";
  $n2m_TEXT[pt][pm]           = "mp";
  $n2m_TEXT[pt][email]        = "email";
  $n2m_TEXT[pt][attachments]  = "Anexos";
  $n2m_TEXT[pt][edited_by]    = "editado por";
  $n2m_TEXT[pt][edit_reason]  = "razão";


  // italian (it)

  $n2m_TEXT[it][mode]         = "Modalit&agrave;";
  $n2m_TEXT[it][forum]        = "Forum";
  $n2m_TEXT[it][thread]       = "Argomento";
  $n2m_TEXT[it][subject]      = "Titolo";
  $n2m_TEXT[it][user]         = "Utente";
  $n2m_TEXT[it][ip_hostname]  = "IP/Host";
  $n2m_TEXT[it][host_na]      = "(n/a)";
  $n2m_TEXT[it][actions]      = "Azioni";
  $n2m_TEXT[it][reply]        = "rispondi";
  $n2m_TEXT[it][quote]        = "cita";
  $n2m_TEXT[it][edit]         = "edita";
  $n2m_TEXT[it][delete]       = "cancella";
  $n2m_TEXT[it][info]         = "info";
  $n2m_TEXT[it][pm]           = "mp";
  $n2m_TEXT[it][email]        = "email";
  $n2m_TEXT[it][attachments]  = "Allegati";
  $n2m_TEXT[it][edited_by]    = "editato da";
  $n2m_TEXT[it][edit_reason]  = "ragione";



  // croatian-hrvatski (hr)

  $n2m_TEXT[hr][mode]         = "Nacin";
  $n2m_TEXT[hr][forum]        = "Forum";
  $n2m_TEXT[hr][thread]       = "Tema";
  $n2m_TEXT[hr][subject]      = "Predmet";
  $n2m_TEXT[hr][user]         = "Korisnik";
  $n2m_TEXT[hr][ip_hostname]  = "IP/Host";
  $n2m_TEXT[hr][host_na]      = "(n.v.)";
  $n2m_TEXT[hr][actions]      = "Akcija";
  $n2m_TEXT[hr][reply]        = "odgovori";
  $n2m_TEXT[hr][quote]        = "citiraj";
  $n2m_TEXT[hr][edit]         = "promjeni";
  $n2m_TEXT[hr][delete]       = "zbriši";
  $n2m_TEXT[hr][info]         = "Informacie";
  $n2m_TEXT[hr][pm]           = "PN";
  $n2m_TEXT[hr][email]        = "eMail";
  $n2m_TEXT[hr][attachments]  = "Prilog";
  $n2m_TEXT[hr][edited_by]    = "promjenjeno od";
  $n2m_TEXT[hr][edit_reason]  = "Razlog";


?>
