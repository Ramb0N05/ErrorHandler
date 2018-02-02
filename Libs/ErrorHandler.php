<?php
/**
 * File: error_handler.inc.php
 * Project: Ticketsystem
 * File Created: Monday, 18th December 2017 1:04:58 pm
 * Author: ramon1611
 * -----
 * Last Modified: Friday, 2nd February 2018 10:16:45 am
 * Modified By: ramon1611
 */

/**
 * Namespace ramon1611\Libs
 */
namespace ramon1611\Libs;

/**
 * Class ErrorHandler
 * 
 * @api
 * @package ErrorHandler
 */
class ErrorHandler {
    private $_excludeFiles = NULL;
    private $_errorStylesheet = NULL;
    private $_noticeCaption = 'Notice!';
    private $_warningCaption = 'Warning!';
    private $_errorCaption = 'Fatal Error!';
    private $_configCompleted = false;

    /**
     * Constructor
     * 
     * @param array $confArr
     * @return void
     */
    public function __construct( array $confArr ) {
        $this->setConfig( $confArr );
    }

    /**
     * Sets the config
     * 
     * @param array $confArr
     * @return void
     */
    public function setConfig( array $confArr ) {
        $this->_excludeFiles = ( isset( $confArr['excludeFiles'] ) ? $confArr['excludeFiles'] : NULL );
        $this->_errorStylesheet = ( isset( $confArr['errorStylesheet'] ) ? $confArr['errorStylesheet'] : NULL );
        $this->_noticeCaption = ( isset( $confArr['noticeCaption'] ) ? $confArr['noticeCaption'] : $this->_noticeCaption );
        $this->_warningCaption = ( isset( $confArr['warningCaption'] ) ? $confArr['warningCaption'] : $this->_warningCaption );
        $this->_errorCaption = ( isset( $confArr['errorCaption'] ) ? $confArr['errorCaption'] : $this->_errorCaption );

        $this->_configCompleted = true;
    }

    /**
     * Handles errors and throws them
     * 
     * @param int $errno The level of the error raised
     * @param string $errstr Error message
     * @param string $errfile The filename that the error was raised in
     * @param int $errline The line number the error was raised at
     * @param array $errcontext Array of every variable that existed in the scope the error was triggered in
     * @return bool
     */
    public function throwError( int $errno, string $errstr, string $errfile, int $errline, array $errcontext ) {
        if ( !( error_reporting() & $errno ) )
            return false;
        else {
            switch( $errno ) {
                case E_USER_ERROR:
                    if ( ob_get_contents() != NULL )
                        ob_clean();
    
                    echo( $this->generateError( $errfile, $errline, $errstr, $errno, $errcontext ) );
                    ob_end_flush();
                    exit(1);
                    break;
    
                case E_USER_WARNING:
                    if ( in_array( basename( $errfile ), $this->_excludeFiles ) )
                        return true;
                    
                    echo( $this->generateWarning( $errfile, $errline, $errstr, $errno ) );
                    break;
                
                case E_USER_NOTICE:
                    if ( in_array( basename( $errfile ), $this->_excludeFiles ) )
                        return true;
    
                    echo( $this->generateNotice( $errfile, $errline, $errstr, $errno ) );
                    break;
    
                case E_ERROR:
                    if ( ob_get_contents() != NULL )
                        ob_clean();
    
                    echo( $this->generateError( $errfile, $errline, $errstr, $errno, $errcontext ) );
                    ob_end_flush();
                    exit(1);
                    break;
    
                case E_WARNING:
                    if ( in_array( basename( $errfile ), $this->_excludeFiles ) )
                        return true;
    
                    echo( $this->generateWarning( $errfile, $errline, $errstr, $errno ) );
                    break;
                case E_NOTICE:
                    if ( in_array( basename( $errfile ), $this->_excludeFiles ) )
                        return true;
    
                    echo( $this->generateNotice( $errfile, $errline, $errstr, $errno ) );
                    break;
                
                case E_COMPILE_ERROR:
                    if ( ob_get_contents() != NULL )
                        ob_clean();

                    echo( $this->generateError( $errfile, $errline, $errstr, $errno, $errcontext ) );
                    ob_end_flush();
                    exit(1);
                    break;

                default:
                    if ( ob_get_contents() != NULL )
                        ob_clean();
    
                    echo( $this->generateError( $errfile, $errline, $errstr, $errno, $errcontext, 'Unknown Error!' ) );
                    ob_end_flush();
                    exit(1);
                    break;
            }
    
            return true;
        }
    }

    /**
     * Registers the handler in PHP
     * 
     * @param void
     * @return mixed Returns the output of set_error_handler() or false if config is not completed
     */
    public function registerHandler() {
        if ( $this->_configCompleted )
            return set_error_handler( [ $this, 'throwError' ] );
        else
            return false;
    }

    /**
     * Generates an error message for a notice
     * 
     * @param string $errfile The filename that the error was raised in
     * @param int $errline The line number the error was raised at
     * @param string $errstr Error message
     * @param int $errno The level of the error raised
     * @return string
     */
    private function generateNotice( string $errfile, int $errline, string $errstr,  int $errno ) {
        $caption = '<div style="font-size: 15pt !important; font-weight: bold !important; padding: 5px 5px 5px 10px !important; border-bottom: 2px solid #888 !important; background-color: #FFF194 !important;">'.$this->_noticeCaption.' ('.$errno.')</div>';
        $content = '<div style="padding: 5px !important; background-color: #E6E6E6 !important;">
                    <p style="margin: 0 auto !important;">Notice occurred in File <b>"'.$errfile.'"</b> in line <b>'.$errline.'</b></p>
                    <p style="margin: 0 auto !important;"><b>Message:</b> '.htmlspecialchars( $errstr ).'</p>
                    </div>';
        $outer = '<div style="margin: 10px !important; border: 2px solid #888 !important; font-family: Calibri, Helvetica, Verdana, Arial !important; font-size: 13pt !important; color: #000 !important; text-align: left !important;">'.$caption.$content.'</div>';

        return $outer;
    }

    /**
     * Generates an error message for a warning
     * 
     * @param string $errfile The filename that the error was raised in
     * @param int $errline The line number the error was raised at
     * @param string $errstr Error message
     * @param int $errno The level of the error raised
     * @return string
     */
    private function generateWarning( string $errfile, int $errline, string $errstr, int $errno ) {
        $caption = '<div style="font-size: 15pt !important; font-weight: bold !important; padding: 5px 5px 5px 10px !important; border-bottom: 2px solid #888 !important; background-color: #F79545 !important;">'.$this->_warningCaption.' ('.$errno.')</div>';
        $content = '<div style="padding: 5px !important; background-color: #E6E6E6 !important;">
                    <p style="margin: 0 auto !important;">Warning occurred in File <b>"'.$errfile.'"</b> in line <b>'.$errline.'</b></p>
                    <p style="margin: 0 auto !important;"><b>Message:</b> '.htmlspecialchars( $errstr ).'</p>
                    </div>';
        $outer = '<div style="margin: 10px !important; border: 2px solid #888 !important; font-family: Calibri, Helvetica, Verdana, Arial !important; font-size: 13pt !important; color: #000 !important; text-align: left !important;">'.$caption.$content.'</div>';

        return $outer;
    }

    /**
     * Generates an error message for a error
     * 
     * @param string $errfile The filename that the error was raised in
     * @param int $errline The line number the error was raised at
     * @param string $errstr Error message
     * @param int $errno The level of the error raised
     * @param array $errcontext Array of every variable that existed in the scope the error was triggered in. Default is NULL
     * @param string $title A custom title of the error message. Default is NULL
     * @return string
     */
    private function generateError( string $errfile, int $errline, string $errstr, int $errno, array $errcontext = NULL, string $title = NULL ) {
        $caption = ( isset( $title ) ? $title : $this->_errorCaption ).' ('.$errno.')';
        $backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 5 );
        $parsedBacktrace = $this->parseBacktrace( $backtrace );
        
        $content = '<p class="errorInfo">Error occurred in File <b>"'.$errfile.'"</b> in line <b>'.$errline.'</b></p>'.
                   '<p class="errorMessage"><b>Message:</b> '.htmlspecialchars( $errstr ).'</p>';
        
        if ( $backtrace != false )
            $content .= '<div class="backtrace"><div class="stCaption">Backtrace</div><pre class="stContent">'.$parsedBacktrace.'</pre></div>';
        
        $return = '<!DOCTYPE html>
        <html>
            <head>
                <title>'.$caption.'</title>
                '.( isset( $this->_errorStylesheet ) ? '<link rel="stylesheet" type="text/css" href="'.$this->_errorStylesheet.'">' : '' ).'
            </head>
            
            <body>
                <header>
                    <span class="mainCaption">'.$caption.'</span>
                </header>
    
                '.$content.'
            </body>
        </html>';
        
        return $return;
    }

    /**
     * Parsing a backtrace as string
     * 
     * @param array $debugBacktrace A backtrace generated by debug_backtrace()
     * @return mixed Returns the string of the backtrace or false if no backtrace data is provided
     */
    private function parseBacktrace( array $debugBacktrace ) {
        if ( isset( $debugBacktrace ) ) {
            $out = '';
    
            foreach ( $debugBacktrace as $btItemNum => $btItem ) {
                $out .= '<strong>'.$btItemNum.'</strong> - ';
    
                if ( isset( $btItem['file'] ) )
                    $out .= 'File: <strong>'.$btItem['file'].'</strong> ';
                if ( isset( $btItem['line'] ) )
                    $out .= 'Line: <strong>'.$btItem['line'].'</strong> ';
                if ( isset( $btItem['function'] ) )
                    $out .= 'Function: <strong>'.$btItem['function'].'</strong> ';
                if ( isset( $btItem['args'] ) ) {
                    $out .= '(Arguments: ';
                    
                    $lastArg = array_pop( $btItem['args'] );
                    foreach ( $btItem['args'] as $arg )
                        $out .= $arg.', ';
                    
                    $out .= $lastArg.')';
                }
    
                $out .= '<br>';
            }
    
            return $out;
        } else
            return false;
    }
}
?>
