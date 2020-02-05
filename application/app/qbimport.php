<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
    */

class qbimport {
    static public function files() {
        $ret = [];

		if ( $path = realpath( sprintf( '%s%sqbdata', config::dataPath(), DIRECTORY_SEPARATOR))) {
            if ( $files = new \GlobIterator( sprintf( '%s/*.csv', $path))) {
                foreach ( $files as $file) {
                    $info = pathinfo( $file->getPathname());

                    $ret[ $info['filename']] = (object)[
                        'name' => $info['filename'],
                        'path' => $file->getPathname()

                    ];

                }

            }

        }

        return $ret;

    }

}