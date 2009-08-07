$MV::Self = {
          'INSTALLDIRS' => 'perl',
          'EXE_FILES' => [
                           'scripts/compile_link',
                           'scripts/config_prog',
                           'scripts/configdump',
                           'scripts/dump',
                           'scripts/expire',
                           'scripts/expireall',
                           'scripts/findtags',
                           'scripts/ic_mod_perl',
                           'scripts/interchange',
                           'scripts/localize',
                           'scripts/makecat',
                           'scripts/offline',
                           'scripts/restart',
                           'scripts/update'
                         ],
          'INSTALLPRIVLIB' => '/usr/local/interchange/lib',
          'INSTALLARCHLIB' => '/usr/local/interchange',
          'PL_FILES' => {
                          'relocate.pl' => [
                                             'scripts/compile_link',
                                             'scripts/config_prog',
                                             'scripts/configdump',
                                             'scripts/dump',
                                             'scripts/expire',
                                             'scripts/expireall',
                                             'scripts/findtags',
                                             'scripts/ic_mod_perl',
                                             'scripts/interchange',
                                             'scripts/localize',
                                             'scripts/makecat',
                                             'scripts/offline',
                                             'scripts/restart',
                                             'scripts/update'
                                           ]
                        },
          'INSTALLMAN1DIR' => '/usr/local/interchange/man',
          'INSTALLSCRIPT' => '/usr/local/interchange/bin',
          'INSTALLBIN' => '/usr/local/interchange/bin',
          'INSTALLMAN3DIR' => '/usr/local/interchange/man'
        }
;
1;