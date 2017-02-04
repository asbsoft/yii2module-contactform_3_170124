<?php
// route without prefix => controller/action without current (and parent) module(s) IDs
return [
    '<action:(view|delete)>/<id:\d+>' => 'admin/<action>',
    '<action:(index)>/<page:\d+>'     => 'admin/<action>',
    '<action:(index)>'                => 'admin/<action>',
    ''                                => 'admin/index',
];
