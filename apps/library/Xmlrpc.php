<?php  if ( ! defined('BASE_PATH')) exit('No direct script access allowed');

/**
 * XML-RPC request handler class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	XML-RPC
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/xmlrpc.html
 */
class Xmlrpc {

	var $debug			= FALSE;	// Debugging on or off
	var $xmlrpcI4		= 'i4';
	var $xmlrpcInt		= 'int';
	var $xmlrpcBoolean	= 'boolean';
	var $xmlrpcDouble	= 'double';
	var $xmlrpcString	= 'string';
	var $xmlrpcDateTime	= 'dateTime.iso8601';
	var $xmlrpcBase64	= 'base64';
	var $xmlrpcArray	= 'array';
	var $xmlrpcStruct	= 'struct';

	var $xmlrpcTypes	= array();
	var $valid_parents	= array();
	var $xmlrpcerr		= array();	// Response numbers
	var $xmlrpcstr		= array();  // Response strings

	var $xmlrpc_defencoding = 'UTF-8';
	var $xmlrpcName			= 'XML-RPC for CodeIgniter';
	var $xmlrpcVersion		= '1.1';
	var $xmlrpcerruser		= 800; // Start of user errors
	var $xmlrpcerrxml		= 100; // Start of XML Parse errors
	var $xmlrpc_backslash	= ''; // formulate backslashes for escaping regexp

	var $client;
	var $method;
	var $data;
	var $message			= '';
	var $error				= '';		// Error string for request
	var $result;
	var $response			= array();  // Response from remote server

	var $xss_clean			= TRUE;

	//-------------------------------------
	//  VALUES THAT MULTIPLE CLASSES NEED
	//-------------------------------------

	public function __construct($config = array())
	{
		$this->xmlrpcName		= $this->xmlrpcName;
		$this->xmlrpc_backslash = chr(92).chr(92);

		// Types for info sent back and forth
		$this->xmlrpcTypes = array(
			$this->xmlrpcI4	 		=> '1',
			$this->xmlrpcInt		=> '1',
			$this->xmlrpcBoolean	=> '1',
			$this->xmlrpcString		=> '1',
			$this->xmlrpcDouble		=> '1',
			$this->xmlrpcDateTime	=> '1',
			$this->xmlrpcBase64		=> '1',
			$this->xmlrpcArray		=> '2',
			$this->xmlrpcStruct		=> '3'
			);

		// Array of Valid Parents for Various XML-RPC elements
		$this->valid_parents = array('BOOLEAN'			=> array('VALUE'),
									 'I4'				=> array('VALUE'),
									 'INT'				=> array('VALUE'),
									 'STRING'			=> array('VALUE'),
									 'DOUBLE'			=> array('VALUE'),
									 'DATETIME.ISO8601'	=> array('VALUE'),
									 'BASE64'			=> array('VALUE'),
									 'ARRAY'			=> array('VALUE'),
									 'STRUCT'			=> array('VALUE'),
									 'PARAM'			=> array('PARAMS'),
									 'METHODNAME'		=> array('METHODCALL'),
									 'PARAMS'			=> array('METHODCALL', 'METHODRESPONSE'),
									 'MEMBER'			=> array('STRUCT'),
									 'NAME'				=> array('MEMBER'),
									 'DATA'				=> array('ARRAY'),
									 'FAULT'			=> array('METHODRESPONSE'),
									 'VALUE'			=> array('MEMBER', 'DATA', 'PARAM', 'FAULT')
									 );


		// XML-RPC Responses
		$this->xmlrpcerr['unknown_method'] = '1';
		$this->xmlrpcstr['unknown_method'] = 'This is not a known method for this XML-RPC Server';
		$this->xmlrpcerr['invalid_return'] = '2';
		$this->xmlrpcstr['invalid_return'] = 'The XML data received was either invalid or not in the correct form for XML-RPC.  Turn on debugging to examine the XML data further.';
		$this->xmlrpcerr['incorrect_params'] = '3';
		$this->xmlrpcstr['incorrect_params'] = 'Incorrect parameters were passed to method';
		$this->xmlrpcerr['introspect_unknown'] = '4';
		$this->xmlrpcstr['introspect_unknown'] = "Cannot inspect signature for request: method unknown";
		$this->xmlrpcerr['http_error'] = '5';
		$this->xmlrpcstr['http_error'] = "Did not receive a '200 OK' response from remote server.";
		$this->xmlrpcerr['no_data'] = '6';
		$this->xmlrpcstr['no_data'] ='No data received from server.';

		$this->initialize($config);
	}


	//-------------------------------------
	//  Initialize Prefs
	//-------------------------------------

	function initialize($config = array())
	{
		if (count($config) > 0)
		{
			foreach ($config as $key => $val)
			{
				if (isset($this->$key))
				{
					$this->$key = $val;
				}
			}
		}
	}
	// END

	//-------------------------------------
	//  Take URL and parse it
	//-------------------------------------

	function server($url, $port=80)
	{
		if (substr($url, 0, 4) != "http")
		{
			$url = "http://".$url;
		}

		$parts = parse_url($url);

		$path = ( ! isset($parts['path'])) ? '/' : $parts['path'];

		if (isset($parts['query']) && $parts['query'] != '')
		{
			$path .= '?'.$parts['query'];
		}

		$this->client = new XML_RPC_Client($path, $parts['host'], $port);
	}
	// END

	//-------------------------------------
	//  Set Timeout
	//-------------------------------------

	function timeout($seconds=5)
	{
		if ( ! is_null($this->client) && is_int($seconds))
		{
			$this->client->timeout = $seconds;
		}
	}
	// END

	//-------------------------------------
	//  Set Methods
	//-------------------------------------

	function method($function)
	{
		$this->method = $function;
	}
	// END

	//-------------------------------------
	//  Take Array of Data and Create Objects
	//-------------------------------------

	function request($incoming)
	{
		if ( ! is_array($incoming))
		{
			// Send Error
		}

		$this->data = array();

		foreach ($incoming as $key => $value)
		{
			$this->data[$key] = $this->values_parsing($value);
		}
	}
	// END


	//-------------------------------------
	//  Set Debug
	//-------------------------------------

	function set_debug($flag = TRUE)
	{
		$this->debug = ($flag == TRUE) ? TRUE : FALSE;
	}

	//-------------------------------------
	//  Values Parsing
	//-------------------------------------

	function values_parsing($value, $return = FALSE)
	{
		if (is_array($value) && array_key_exists(0, $value))
		{
			if ( ! isset($value['1']) OR ( ! isset($this->xmlrpcTypes[$value['1']])))
			{
				if (is_array($value[0]))
				{
					$temp = new XML_RPC_Values($value['0'], 'array');
				}
				else
				{
					$temp = new XML_RPC_Values($value['0'], 'string');
				}
			}
			elseif (is_array($value['0']) && ($value['1'] == 'struct' OR $value['1'] == 'array'))
			{
				while (list($k) = each($value['0']))
				{
					$value['0'][$k] = $this->values_parsing($value['0'][$k], TRUE);
				}

				$temp = new XML_RPC_Values($value['0'], $value['1']);
			}
			else
			{
				$temp = new XML_RPC_Values($value['0'], $value['1']);
			}
		}
		else
		{
			$temp = new XML_RPC_Values($value, 'string');
		}

		return $temp;
	}
	// END


	//-------------------------------------
	//  Sends XML-RPC Request
	//-------------------------------------

	function send_request()
	{
		$this->message = new XML_RPC_Message($this->method,$this->data);
		$this->message->debug = $this->debug;

		if ( ! $this->result = $this->client->send($this->message))
		{
			$this->error = $this->result->errstr;
			return FALSE;
		}
		elseif ( ! is_object($this->result->val))
		{
			$this->error = $this->result->errstr;
			return FALSE;
		}

		$this->response = $this->result->decode();

		return TRUE;
	}
	// END

	//-------------------------------------
	//  Returns Error
	//-------------------------------------

	function display_error()
	{
		return $this->error;
	}
	// END

	//-------------------------------------
	//  Returns Remote Server Response
	//-------------------------------------

	function display_response()
	{
		return $this->response;
	}
	// END

	//-------------------------------------
	//  Sends an Error Message for Server Request
	//-------------------------------------

	function send_error_message($number, $message)
	{
		return new XML_RPC_Response('0',$number, $message);
	}
	// END


	//-------------------------------------
	//  Send Response for Server Request
	//-------------------------------------

	function send_response($response)
	{
		// $response should be array of values, which will be parsed
		// based on their data and type into a valid group of XML-RPC values

		$response = $this->values_parsing($response);

		return new XML_RPC_Response($response);
	}
	// END

} // END XML_RPC Class



/**
 * XML-RPC Client class
 *
 * @category	XML-RPC
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/xmlrpc.html
 */
class XML_RPC_Client extends Xmlrpc
{
	var $path			= '';
	var $server			= '';
	var $port			= 80;
	var $errno			= '';
	var $errstring		= '';
	var $timeout		= 5;
	var $no_multicall	= FALSE;

	public function __construct($path, $server, $port=80)
	{
		parent::__construct();

		$this->port = $port;
		$this->server = $server;
		$this->path = $path;
	}

	function send($msg)
	{
		if (is_array($msg))
		{
			// Multi-call disabled
			$r = new XML_RPC_Response(0, $this->xmlrpcerr['multicall_recursion'],$this->xmlrpcstr['multicall_recursion']);
			return $r;
		}

		return $this->sendPayload($msg);
	}

	function sendPayload($msg)
	{
		$fp = @fsockopen($this->server, $this->port,$this->errno, $this->errstr, $this->timeout);

		if ( ! is_resource($fp))
		{
			error_log($this->xmlrpcstr['http_error']);
			$r = new XML_RPC_Response(0, $this->xmlrpcerr['http_error'],$this->xmlrpcstr['http_error']);
			return $r;
		}

		if (empty($msg->payload))
		{
			// $msg = XML_RPC_Messages
			$msg->createPayload();
		}

		$r = "\r\n";
		$op  = "POST {$this->path} HTTP/1.0$r";
		$op .= "Host: {$this->server}$r";
		$op .= "Content-Type: text/xml$r";
		$op .= "User-Agent: {$this->xmlrpcName}$r";
		$op .= "Content-Length: ".strlen($msg->payload). "$r$r";
		$op .= $msg->payload;


		if ( ! fputs($fp, $op, strlen($op)))
		{
			error_log($this->xmlrpcstr['http_error']);
			$r = new XML_RPC_Response(0, $this->xmlrpcerr['http_error'], $this->xmlrpcstr['http_error']);
			return $r;
		}
		$resp = $msg->parseResponse($fp);
		fclose($fp);
		return $resp;
	}

} // end class XML_RPC_Client


/**
 * XML-RPC Response class
 *
 * @category	XML-RPC
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/xmlrpc.html
 */
class XML_RPC_Response
{
	var $val = 0;
	var $errno = 0;
	var $errstr = '';
	var $headers = array();
	var $xss_clean = TRUE;

	public function __construct($val, $code = 0, $fstr = '')
	{
		if ($code != 0)
		{
			// error
			$this->errno = $code;
			$this->errstr = htmlentities($fstr);
		}
		else if ( ! is_object($val))
		{
			// programmer error, not an object
			error_log("Invalid type '" . gettype($val) . "' (value: $val) passed to XML_RPC_Response.  Defaulting to empty value.");
			$this->val = new XML_RPC_Values();
		}
		else
		{
			$this->val = $val;
		}
	}

	function faultCode()
	{
		return $this->errno;
	}

	function faultString()
	{
		return $this->errstr;
	}

	function value()
	{
		return $this->val;
	}

	function prepare_response()
	{
		$result = "<methodResponse>\n";
		if ($this->errno)
		{
			$result .= '<fault>
	<value>
		<struct>
			<member>
				<name>faultCode</name>
				<value><int>' . $this->errno . '</int></value>
			</member>
			<member>
				<name>faultString</name>
				<value><string>' . $this->errstr . '</string></value>
			</member>
		</struct>
	</value>
</fault>';
		}
		else
		{
			$result .= "<params>\n<param>\n" .
					$this->val->serialize_class() .
					"</param>\n</params>";
		}
		$result .= "\n</methodResponse>";
		return $result;
	}

    protected function _do_never_allowed($str)
    {
        $str = str_replace(array_keys($this->_never_allowed_str), $this->_never_allowed_str, $str);

        foreach ($this->_never_allowed_regex as $regex)
        {
            $str = preg_replace('#'.$regex.'#is', '[removed]', $str);
        }

        return $str;
    }

    public function remove_invisible_characters($str, $url_encoded = TRUE)
    {
        $non_displayables = array();

        // every control character except newline (dec 10),
        // carriage return (dec 13) and horizontal tab (dec 09)
        if ($url_encoded)
        {
            $non_displayables[] = '/%0[0-8bcef]/';	// url encoded 00-08, 11, 12, 14, 15
            $non_displayables[] = '/%1[0-9a-f]/';	// url encoded 16-31
        }

        $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

        do
        {
            $str = preg_replace($non_displayables, '', $str, -1, $count);
        }
        while ($count);

        return $str;
    }

    protected function _remove_evil_attributes($str, $is_image)
    {
        $evil_attributes = array('on\w*', 'style', 'xmlns', 'formaction', 'form', 'xlink:href');

        if ($is_image === TRUE)
        {
            /*
             * Adobe Photoshop puts XML metadata into JFIF images,
             * including namespacing, so we have to allow this for images.
             */
            unset($evil_attributes[array_search('xmlns', $evil_attributes)]);
        }

        do {
            $count = 0;
            $attribs = array();

            // find occurrences of illegal attribute strings with quotes (042 and 047 are octal quotes)
            preg_match_all('/(?<!\w)('.implode('|', $evil_attributes).')\s*=\s*(\042|\047)([^\\2]*?)(\\2)/is', $str, $matches, PREG_SET_ORDER);

            foreach ($matches as $attr)
            {
                $attribs[] = preg_quote($attr[0], '/');
            }

            // find occurrences of illegal attribute strings without quotes
            preg_match_all('/(?<!\w)('.implode('|', $evil_attributes).')\s*=\s*([^\s>]*)/is', $str, $matches, PREG_SET_ORDER);

            foreach ($matches as $attr)
            {
                $attribs[] = preg_quote($attr[0], '/');
            }

            // replace illegal attribute strings that are inside an html tag
            if (count($attribs) > 0)
            {
                $str = preg_replace('/(<?)(\/?[^><]+?)([^A-Za-z<>\-])(.*?)('.implode('|', $attribs).')(.*?)([\s><]?)([><]*)/i', '$1$2 $4$6$7$8', $str, -1, $count);
            }
        }
        while ($count);

        return $str;
    }

    public function xss_clean($str, $is_image = FALSE)
    {
        // Is the string an array?
        if (is_array($str))
        {
            while (list($key) = each($str))
            {
                $str[$key] = $this->xss_clean($str[$key]);
            }

            return $str;
        }

        // Remove Invisible Characters
        $str = $this->remove_invisible_characters($str);

        /*
         * URL Decode
         *
         * Just in case stuff like this is submitted:
         *
         * <a href="http://%77%77%77%2E%67%6F%6F%67%6C%65%2E%63%6F%6D">Google</a>
         *
         * Note: Use rawurldecode() so it does not remove plus signs
         */
        do
        {
            $str = rawurldecode($str);
        }
        while (preg_match('/%[0-9a-f]{2,}/i', $str));

        /*
         * Convert character entities to ASCII
         *
         * This permits our tests below to work reliably.
         * We only convert entities that are within tags since
         * these are the ones that will pose security problems.
         */
        $str = preg_replace_callback("/[^a-z0-9>]+[a-z0-9]+=([\'\"]).*?\\1/si", array($this, '_convert_attribute'), $str);
        $str = preg_replace_callback('/<\w+.*/si', array($this, '_decode_entity'), $str);

        // Remove Invisible Characters Again!
        $str = $this->remove_invisible_characters($str);

        /*
         * Convert all tabs to spaces
         *
         * This prevents strings like this: ja	vascript
         * NOTE: we deal with spaces between characters later.
         * NOTE: preg_replace was found to be amazingly slow here on
         * large blocks of data, so we use str_replace.
         */
        $str = str_replace("\t", ' ', $str);

        // Capture converted string for later comparison
        $converted_string = $str;

        // Remove Strings that are never allowed
        $str = $this->_do_never_allowed($str);

        /*
         * Makes PHP tags safe
         *
         * Note: XML tags are inadvertently replaced too:
         *
         * <?xml
         *
         * But it doesn't seem to pose a problem.
         */
        if ($is_image === TRUE)
        {
            // Images have a tendency to have the PHP short opening and
            // closing tags every so often so we skip those and only
            // do the long opening tags.
            $str = preg_replace('/<\?(php)/i', '&lt;?\\1', $str);
        }
        else
        {
            $str = str_replace(array('<?', '?'.'>'), array('&lt;?', '?&gt;'), $str);
        }

        /*
         * Compact any exploded words
         *
         * This corrects words like:  j a v a s c r i p t
         * These words are compacted back to their correct state.
         */
        $words = array(
            'javascript', 'expression', 'vbscript', 'jscript', 'wscript',
            'vbs', 'script', 'base64', 'applet', 'alert', 'document',
            'write', 'cookie', 'window', 'confirm', 'prompt'
        );

        foreach ($words as $word)
        {
            $word = implode('\s*', str_split($word)).'\s*';

            // We only want to do this when it is followed by a non-word character
            // That way valid stuff like "dealer to" does not become "dealerto"
            $str = preg_replace_callback('#('.substr($word, 0, -3).')(\W)#is', array($this, '_compact_exploded_words'), $str);
        }

        /*
         * Remove disallowed Javascript in links or img tags
         * We used to do some version comparisons and use of stripos(),
         * but it is dog slow compared to these simplified non-capturing
         * preg_match(), especially if the pattern exists in the string
         *
         * Note: It was reported that not only space characters, but all in
         * the following pattern can be parsed as separators between a tag name
         * and its attributes: [\d\s"\'`;,\/\=\(\x00\x0B\x09\x0C]
         * ... however, remove_invisible_characters() above already strips the
         * hex-encoded ones, so we'll skip them below.
         */
        do
        {
            $original = $str;

            if (preg_match('/<a/i', $str))
            {
                $str = preg_replace_callback('#<a[^a-z0-9>]+([^>]*?)(?:>|$)#si', array($this, '_js_link_removal'), $str);
            }

            if (preg_match('/<img/i', $str))
            {
                $str = preg_replace_callback('#<img[^a-z0-9]+([^>]*?)(?:\s?/?>|$)#si', array($this, '_js_img_removal'), $str);
            }

            if (preg_match('/script|xss/i', $str))
            {
                $str = preg_replace('#</*(?:script|xss).*?>#si', '[removed]', $str);
            }
        }
        while ($original !== $str);

        unset($original);

        // Remove evil attributes such as style, onclick and xmlns
        $str = $this->_remove_evil_attributes($str, $is_image);

        /*
         * Sanitize naughty HTML elements
         *
         * If a tag containing any of the words in the list
         * below is found, the tag gets converted to entities.
         *
         * So this: <blink>
         * Becomes: &lt;blink&gt;
         */
        $naughty = 'alert|prompt|confirm|applet|audio|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|button|select|isindex|layer|link|meta|keygen|object|plaintext|style|script|textarea|title|math|video|svg|xml|xss';
        $str = preg_replace_callback('#<(/*\s*)('.$naughty.')([^><]*)([><]*)#is', array($this, '_sanitize_naughty_html'), $str);

        /*
         * Sanitize naughty scripting elements
         *
         * Similar to above, only instead of looking for
         * tags it looks for PHP and JavaScript commands
         * that are disallowed. Rather than removing the
         * code, it simply converts the parenthesis to entities
         * rendering the code un-executable.
         *
         * For example:	eval('some code')
         * Becomes:	eval&#40;'some code'&#41;
         */
        $str = preg_replace('#(alert|prompt|confirm|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si',
            '\\1\\2&#40;\\3&#41;',
            $str);

        // Final clean up
        // This adds a bit of extra precaution in case
        // something got through the above filters
        $str = $this->_do_never_allowed($str);

        /*
         * Images are Handled in a Special Way
         * - Essentially, we want to know that after all of the character
         * conversion is done whether any unwanted, likely XSS, code was found.
         * If not, we return TRUE, as the image is clean.
         * However, if the string post-conversion does not matched the
         * string post-removal of XSS, then it fails, as there was unwanted XSS
         * code found and removed/changed during processing.
         */
        if ($is_image === TRUE)
        {
            return ($str === $converted_string);
        }
        return $str;
    }

	function decode($array=FALSE)
	{
		
		if ($array !== FALSE && is_array($array))
		{
			while (list($key) = each($array))
			{
				if (is_array($array[$key]))
				{
					$array[$key] = $this->decode($array[$key]);
				}
				else
				{
					$array[$key] = ($this->xss_clean) ? $this->xss_clean($array[$key]) : $array[$key];
				}
			}

			$result = $array;
		}
		else
		{
			$result = $this->xmlrpc_decoder($this->val);

			if (is_array($result))
			{
				$result = $this->decode($result);
			}
			else
			{
				$result = ($this->xss_clean) ? $this->xss_clean($result) : $result;
			}
		}

		return $result;
	}



	//-------------------------------------
	//  XML-RPC Object to PHP Types
	//-------------------------------------

	function xmlrpc_decoder($xmlrpc_val)
	{
		$kind = $xmlrpc_val->kindOf();

		if ($kind == 'scalar')
		{
			return $xmlrpc_val->scalarval();
		}
		elseif ($kind == 'array')
		{
			reset($xmlrpc_val->me);
			list($a,$b) = each($xmlrpc_val->me);
			$size = count($b);

			$arr = array();

			for ($i = 0; $i < $size; $i++)
			{
				$arr[] = $this->xmlrpc_decoder($xmlrpc_val->me['array'][$i]);
			}
			return $arr;
		}
		elseif ($kind == 'struct')
		{
			reset($xmlrpc_val->me['struct']);
			$arr = array();

			while (list($key,$value) = each($xmlrpc_val->me['struct']))
			{
				$arr[$key] = $this->xmlrpc_decoder($value);
			}
			return $arr;
		}
	}


	//-------------------------------------
	//  ISO-8601 time to server or UTC time
	//-------------------------------------

	function iso8601_decode($time, $utc=0)
	{
		// return a timet in the localtime, or UTC
		$t = 0;
		if (preg_match('/([0-9]{4})([0-9]{2})([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})/', $time, $regs))
		{
			$fnc = ($utc == 1) ? 'gmmktime' : 'mktime';
			$t = $fnc($regs[4], $regs[5], $regs[6], $regs[2], $regs[3], $regs[1]);
		}
		return $t;
	}

} // End Response Class



/**
 * XML-RPC Message class
 *
 * @category	XML-RPC
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/xmlrpc.html
 */
class XML_RPC_Message extends Xmlrpc
{
	var $payload;
	var $method_name;
	var $params			= array();
	var $xh				= array();

	public function __construct($method, $pars=0)
	{
		parent::__construct();

		$this->method_name = $method;
		if (is_array($pars) && count($pars) > 0)
		{
			for ($i=0; $i<count($pars); $i++)
			{
				// $pars[$i] = XML_RPC_Values
				$this->params[] = $pars[$i];
			}
		}
	}

	//-------------------------------------
	//  Create Payload to Send
	//-------------------------------------

	function createPayload()
	{
		$this->payload = "<?xml version=\"1.0\"?".">\r\n<methodCall>\r\n";
		$this->payload .= '<methodName>' . $this->method_name . "</methodName>\r\n";
		$this->payload .= "<params>\r\n";

		for ($i=0; $i<count($this->params); $i++)
		{
			// $p = XML_RPC_Values
			$p = $this->params[$i];
			$this->payload .= "<param>\r\n".$p->serialize_class()."</param>\r\n";
		}

		$this->payload .= "</params>\r\n</methodCall>\r\n";
	}

	//-------------------------------------
	//  Parse External XML-RPC Server's Response
	//-------------------------------------

	function parseResponse($fp)
	{
		$data = '';

		while ($datum = fread($fp, 4096))
		{
			$data .= $datum;
		}

		//-------------------------------------
		//  DISPLAY HTTP CONTENT for DEBUGGING
		//-------------------------------------

		if ($this->debug === TRUE)
		{
			echo "<pre>";
			echo "---DATA---\n" . htmlspecialchars($data) . "\n---END DATA---\n\n";
			echo "</pre>";
		}

		//-------------------------------------
		//  Check for data
		//-------------------------------------

		if ($data == "")
		{
			error_log($this->xmlrpcstr['no_data']);
			$r = new XML_RPC_Response(0, $this->xmlrpcerr['no_data'], $this->xmlrpcstr['no_data']);
			return $r;
		}


		//-------------------------------------
		//  Check for HTTP 200 Response
		//-------------------------------------

		if (strncmp($data, 'HTTP', 4) == 0 && ! preg_match('/^HTTP\/[0-9\.]+ 200 /', $data))
		{
			$errstr= substr($data, 0, strpos($data, "\n")-1);
			$r = new XML_RPC_Response(0, $this->xmlrpcerr['http_error'], $this->xmlrpcstr['http_error']. ' (' . $errstr . ')');
			return $r;
		}

		//-------------------------------------
		//  Create and Set Up XML Parser
		//-------------------------------------

		$parser = xml_parser_create($this->xmlrpc_defencoding);

		$this->xh[$parser]					= array();
		$this->xh[$parser]['isf']			= 0;
		$this->xh[$parser]['ac']			= '';
		$this->xh[$parser]['headers']		= array();
		$this->xh[$parser]['stack']			= array();
		$this->xh[$parser]['valuestack']	= array();
		$this->xh[$parser]['isf_reason']	= 0;

		xml_set_object($parser, $this);
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, true);
		xml_set_element_handler($parser, 'open_tag', 'closing_tag');
		xml_set_character_data_handler($parser, 'character_data');
		//xml_set_default_handler($parser, 'default_handler');


		//-------------------------------------
		//  GET HEADERS
		//-------------------------------------

		$lines = explode("\r\n", $data);
		while (($line = array_shift($lines)))
		{
			if (strlen($line) < 1)
			{
				break;
			}
			$this->xh[$parser]['headers'][] = $line;
		}
		$data = implode("\r\n", $lines);


		//-------------------------------------
		//  PARSE XML DATA
		//-------------------------------------

		if ( ! xml_parse($parser, $data, count($data)))
		{
			$errstr = sprintf('XML error: %s at line %d',
					xml_error_string(xml_get_error_code($parser)),
					xml_get_current_line_number($parser));
			//error_log($errstr);
			$r = new XML_RPC_Response(0, $this->xmlrpcerr['invalid_return'], $this->xmlrpcstr['invalid_return']);
			xml_parser_free($parser);
			return $r;
		}
		xml_parser_free($parser);

		// ---------------------------------------
		//  Got Ourselves Some Badness, It Seems
		// ---------------------------------------

		if ($this->xh[$parser]['isf'] > 1)
		{
			if ($this->debug === TRUE)
			{
				echo "---Invalid Return---\n";
				echo $this->xh[$parser]['isf_reason'];
				echo "---Invalid Return---\n\n";
			}

			$r = new XML_RPC_Response(0, $this->xmlrpcerr['invalid_return'],$this->xmlrpcstr['invalid_return'].' '.$this->xh[$parser]['isf_reason']);
			return $r;
		}
		elseif ( ! is_object($this->xh[$parser]['value']))
		{
			$r = new XML_RPC_Response(0, $this->xmlrpcerr['invalid_return'],$this->xmlrpcstr['invalid_return'].' '.$this->xh[$parser]['isf_reason']);
			return $r;
		}

		//-------------------------------------
		//  DISPLAY XML CONTENT for DEBUGGING
		//-------------------------------------

		if ($this->debug === TRUE)
		{
			echo "<pre>";

			if (count($this->xh[$parser]['headers'] > 0))
			{
				echo "---HEADERS---\n";
				foreach ($this->xh[$parser]['headers'] as $header)
				{
					echo "$header\n";
				}
				echo "---END HEADERS---\n\n";
			}

			echo "---DATA---\n" . htmlspecialchars($data) . "\n---END DATA---\n\n";

			echo "---PARSED---\n" ;
			var_dump($this->xh[$parser]['value']);
			echo "\n---END PARSED---</pre>";
		}

		//-------------------------------------
		//  SEND RESPONSE
		//-------------------------------------

		$v = $this->xh[$parser]['value'];

		if ($this->xh[$parser]['isf'])
		{
			$errno_v = $v->me['struct']['faultCode'];
			$errstr_v = $v->me['struct']['faultString'];
			$errno = $errno_v->scalarval();

			if ($errno == 0)
			{
				// FAULT returned, errno needs to reflect that
				$errno = -1;
			}

			$r = new XML_RPC_Response($v, $errno, $errstr_v->scalarval());
		}
		else
		{
			$r = new XML_RPC_Response($v);
		}

		$r->headers = $this->xh[$parser]['headers'];
		return $r;
	}

	// ------------------------------------
	//  Begin Return Message Parsing section
	// ------------------------------------

	// quick explanation of components:
	//   ac - used to accumulate values
	//   isf - used to indicate a fault
	//   lv - used to indicate "looking for a value": implements
	//		the logic to allow values with no types to be strings
	//   params - used to store parameters in method calls
	//   method - used to store method name
	//	 stack - array with parent tree of the xml element,
	//			 used to validate the nesting of elements

	//-------------------------------------
	//  Start Element Handler
	//-------------------------------------

	function open_tag($the_parser, $name, $attrs)
	{
		// If invalid nesting, then return
		if ($this->xh[$the_parser]['isf'] > 1) return;

		// Evaluate and check for correct nesting of XML elements

		if (count($this->xh[$the_parser]['stack']) == 0)
		{
			if ($name != 'METHODRESPONSE' && $name != 'METHODCALL')
			{
				$this->xh[$the_parser]['isf'] = 2;
				$this->xh[$the_parser]['isf_reason'] = 'Top level XML-RPC element is missing';
				return;
			}
		}
		else
		{
			// not top level element: see if parent is OK
			if ( ! in_array($this->xh[$the_parser]['stack'][0], $this->valid_parents[$name], TRUE))
			{
				$this->xh[$the_parser]['isf'] = 2;
				$this->xh[$the_parser]['isf_reason'] = "XML-RPC element $name cannot be child of ".$this->xh[$the_parser]['stack'][0];
				return;
			}
		}

		switch($name)
		{
			case 'STRUCT':
			case 'ARRAY':
				// Creates array for child elements

				$cur_val = array('value' => array(),
								 'type'	 => $name);

				array_unshift($this->xh[$the_parser]['valuestack'], $cur_val);
			break;
			case 'METHODNAME':
			case 'NAME':
				$this->xh[$the_parser]['ac'] = '';
			break;
			case 'FAULT':
				$this->xh[$the_parser]['isf'] = 1;
			break;
			case 'PARAM':
				$this->xh[$the_parser]['value'] = NULL;
			break;
			case 'VALUE':
				$this->xh[$the_parser]['vt'] = 'value';
				$this->xh[$the_parser]['ac'] = '';
				$this->xh[$the_parser]['lv'] = 1;
			break;
			case 'I4':
			case 'INT':
			case 'STRING':
			case 'BOOLEAN':
			case 'DOUBLE':
			case 'DATETIME.ISO8601':
			case 'BASE64':
				if ($this->xh[$the_parser]['vt'] != 'value')
				{
					//two data elements inside a value: an error occurred!
					$this->xh[$the_parser]['isf'] = 2;
					$this->xh[$the_parser]['isf_reason'] = "'Twas a $name element following a ".$this->xh[$the_parser]['vt']." element inside a single value";
					return;
				}

				$this->xh[$the_parser]['ac'] = '';
			break;
			case 'MEMBER':
				// Set name of <member> to nothing to prevent errors later if no <name> is found
				$this->xh[$the_parser]['valuestack'][0]['name'] = '';

				// Set NULL value to check to see if value passed for this param/member
				$this->xh[$the_parser]['value'] = NULL;
			break;
			case 'DATA':
			case 'METHODCALL':
			case 'METHODRESPONSE':
			case 'PARAMS':
				// valid elements that add little to processing
			break;
			default:
				/// An Invalid Element is Found, so we have trouble
				$this->xh[$the_parser]['isf'] = 2;
				$this->xh[$the_parser]['isf_reason'] = "Invalid XML-RPC element found: $name";
			break;
		}

		// Add current element name to stack, to allow validation of nesting
		array_unshift($this->xh[$the_parser]['stack'], $name);

		if ($name != 'VALUE') $this->xh[$the_parser]['lv'] = 0;
	}
	// END


	//-------------------------------------
	//  End Element Handler
	//-------------------------------------

	function closing_tag($the_parser, $name)
	{
		if ($this->xh[$the_parser]['isf'] > 1) return;

		// Remove current element from stack and set variable
		// NOTE: If the XML validates, then we do not have to worry about
		// the opening and closing of elements.  Nesting is checked on the opening
		// tag so we be safe there as well.

		$curr_elem = array_shift($this->xh[$the_parser]['stack']);

		switch($name)
		{
			case 'STRUCT':
			case 'ARRAY':
				$cur_val = array_shift($this->xh[$the_parser]['valuestack']);
				$this->xh[$the_parser]['value'] = ( ! isset($cur_val['values'])) ? array() : $cur_val['values'];
				$this->xh[$the_parser]['vt']	= strtolower($name);
			break;
			case 'NAME':
				$this->xh[$the_parser]['valuestack'][0]['name'] = $this->xh[$the_parser]['ac'];
			break;
			case 'BOOLEAN':
			case 'I4':
			case 'INT':
			case 'STRING':
			case 'DOUBLE':
			case 'DATETIME.ISO8601':
			case 'BASE64':
				$this->xh[$the_parser]['vt'] = strtolower($name);

				if ($name == 'STRING')
				{
					$this->xh[$the_parser]['value'] = $this->xh[$the_parser]['ac'];
				}
				elseif ($name=='DATETIME.ISO8601')
				{
					$this->xh[$the_parser]['vt']	= $this->xmlrpcDateTime;
					$this->xh[$the_parser]['value'] = $this->xh[$the_parser]['ac'];
				}
				elseif ($name=='BASE64')
				{
					$this->xh[$the_parser]['value'] = base64_decode($this->xh[$the_parser]['ac']);
				}
				elseif ($name=='BOOLEAN')
				{
					// Translated BOOLEAN values to TRUE AND FALSE
					if ($this->xh[$the_parser]['ac'] == '1')
					{
						$this->xh[$the_parser]['value'] = TRUE;
					}
					else
					{
						$this->xh[$the_parser]['value'] = FALSE;
					}
				}
				elseif ($name=='DOUBLE')
				{
					// we have a DOUBLE
					// we must check that only 0123456789-.<space> are characters here
					if ( ! preg_match('/^[+-]?[eE0-9\t \.]+$/', $this->xh[$the_parser]['ac']))
					{
						$this->xh[$the_parser]['value'] = 'ERROR_NON_NUMERIC_FOUND';
					}
					else
					{
						$this->xh[$the_parser]['value'] = (double)$this->xh[$the_parser]['ac'];
					}
				}
				else
				{
					// we have an I4/INT
					// we must check that only 0123456789-<space> are characters here
					if ( ! preg_match('/^[+-]?[0-9\t ]+$/', $this->xh[$the_parser]['ac']))
					{
						$this->xh[$the_parser]['value'] = 'ERROR_NON_NUMERIC_FOUND';
					}
					else
					{
						$this->xh[$the_parser]['value'] = (int)$this->xh[$the_parser]['ac'];
					}
				}
				$this->xh[$the_parser]['ac'] = '';
				$this->xh[$the_parser]['lv'] = 3; // indicate we've found a value
			break;
			case 'VALUE':
				// This if() detects if no scalar was inside <VALUE></VALUE>
				if ($this->xh[$the_parser]['vt']=='value')
				{
					$this->xh[$the_parser]['value']	= $this->xh[$the_parser]['ac'];
					$this->xh[$the_parser]['vt']	= $this->xmlrpcString;
				}

				// build the XML-RPC value out of the data received, and substitute it
				$temp = new XML_RPC_Values($this->xh[$the_parser]['value'], $this->xh[$the_parser]['vt']);

				if (count($this->xh[$the_parser]['valuestack']) && $this->xh[$the_parser]['valuestack'][0]['type'] == 'ARRAY')
				{
					// Array
					$this->xh[$the_parser]['valuestack'][0]['values'][] = $temp;
				}
				else
				{
					// Struct
					$this->xh[$the_parser]['value'] = $temp;
				}
			break;
			case 'MEMBER':
				$this->xh[$the_parser]['ac']='';

				// If value add to array in the stack for the last element built
				if ($this->xh[$the_parser]['value'])
				{
					$this->xh[$the_parser]['valuestack'][0]['values'][$this->xh[$the_parser]['valuestack'][0]['name']] = $this->xh[$the_parser]['value'];
				}
			break;
			case 'DATA':
				$this->xh[$the_parser]['ac']='';
			break;
			case 'PARAM':
				if ($this->xh[$the_parser]['value'])
				{
					$this->xh[$the_parser]['params'][] = $this->xh[$the_parser]['value'];
				}
			break;
			case 'METHODNAME':
				$this->xh[$the_parser]['method'] = ltrim($this->xh[$the_parser]['ac']);
			break;
			case 'PARAMS':
			case 'FAULT':
			case 'METHODCALL':
			case 'METHORESPONSE':
				// We're all good kids with nuthin' to do
			break;
			default:
				// End of an Invalid Element.  Taken care of during the opening tag though
			break;
		}
	}

	//-------------------------------------
	//  Parses Character Data
	//-------------------------------------

	function character_data($the_parser, $data)
	{
		if ($this->xh[$the_parser]['isf'] > 1) return; // XML Fault found already

		// If a value has not been found
		if ($this->xh[$the_parser]['lv'] != 3)
		{
			if ($this->xh[$the_parser]['lv'] == 1)
			{
				$this->xh[$the_parser]['lv'] = 2; // Found a value
			}

			if ( ! @isset($this->xh[$the_parser]['ac']))
			{
				$this->xh[$the_parser]['ac'] = '';
			}

			$this->xh[$the_parser]['ac'] .= $data;
		}
	}


	function addParam($par) { $this->params[]=$par; }

	function output_parameters($array=FALSE)
	{
		if ($array !== FALSE && is_array($array))
		{
			while (list($key) = each($array))
			{
				if (is_array($array[$key]))
				{
					$array[$key] = $this->output_parameters($array[$key]);
				}
				else
				{
					// 'bits' is for the MetaWeblog API image bits
					// @todo - this needs to be made more general purpose
					$array[$key] = ($key == 'bits' OR $this->xss_clean == FALSE) ? $array[$key] : $this->xss_clean($array[$key]);
				}
			}

			$parameters = $array;
		}
		else
		{
			$parameters = array();

			for ($i = 0; $i < count($this->params); $i++)
			{
				$a_param = $this->decode_message($this->params[$i]);

				if (is_array($a_param))
				{
					$parameters[] = $this->output_parameters($a_param);
				}
				else
				{
					$parameters[] = ($this->xss_clean) ? $this->xss_clean($a_param) : $a_param;
				}
			}
		}

		return $parameters;
	}


	function decode_message($param)
	{
		$kind = $param->kindOf();

		if ($kind == 'scalar')
		{
			return $param->scalarval();
		}
		elseif ($kind == 'array')
		{
			reset($param->me);
			list($a,$b) = each($param->me);

			$arr = array();

			for($i = 0; $i < count($b); $i++)
			{
				$arr[] = $this->decode_message($param->me['array'][$i]);
			}

			return $arr;
		}
		elseif ($kind == 'struct')
		{
			reset($param->me['struct']);

			$arr = array();

			while (list($key,$value) = each($param->me['struct']))
			{
				$arr[$key] = $this->decode_message($value);
			}

			return $arr;
		}
	}

} // End XML_RPC_Messages class



/**
 * XML-RPC Values class
 *
 * @category	XML-RPC
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/xmlrpc.html
 */
class XML_RPC_Values extends Xmlrpc
{
	var $me		= array();
	var $mytype	= 0;

	public function __construct($val=-1, $type='')
	{
		parent::__construct();

		if ($val != -1 OR $type != '')
		{
			$type = $type == '' ? 'string' : $type;

			if ($this->xmlrpcTypes[$type] == 1)
			{
				$this->addScalar($val,$type);
			}
			elseif ($this->xmlrpcTypes[$type] == 2)
			{
				$this->addArray($val);
			}
			elseif ($this->xmlrpcTypes[$type] == 3)
			{
				$this->addStruct($val);
			}
		}
	}

	function addScalar($val, $type='string')
	{
		$typeof = $this->xmlrpcTypes[$type];

		if ($this->mytype==1)
		{
			echo '<strong>XML_RPC_Values</strong>: scalar can have only one value<br />';
			return 0;
		}

		if ($typeof != 1)
		{
			echo '<strong>XML_RPC_Values</strong>: not a scalar type (${typeof})<br />';
			return 0;
		}

		if ($type == $this->xmlrpcBoolean)
		{
			if (strcasecmp($val,'true')==0 OR $val==1 OR ($val==true && strcasecmp($val,'false')))
			{
				$val = 1;
			}
			else
			{
				$val=0;
			}
		}

		if ($this->mytype == 2)
		{
			// adding to an array here
			$ar = $this->me['array'];
			$ar[] = new XML_RPC_Values($val, $type);
			$this->me['array'] = $ar;
		}
		else
		{
			// a scalar, so set the value and remember we're scalar
			$this->me[$type] = $val;
			$this->mytype = $typeof;
		}
		return 1;
	}

	function addArray($vals)
	{
		if ($this->mytype != 0)
		{
			echo '<strong>XML_RPC_Values</strong>: already initialized as a [' . $this->kindOf() . ']<br />';
			return 0;
		}

		$this->mytype = $this->xmlrpcTypes['array'];
		$this->me['array'] = $vals;
		return 1;
	}

	function addStruct($vals)
	{
		if ($this->mytype != 0)
		{
			echo '<strong>XML_RPC_Values</strong>: already initialized as a [' . $this->kindOf() . ']<br />';
			return 0;
		}
		$this->mytype = $this->xmlrpcTypes['struct'];
		$this->me['struct'] = $vals;
		return 1;
	}

	function kindOf()
	{
		switch($this->mytype)
		{
			case 3:
				return 'struct';
				break;
			case 2:
				return 'array';
				break;
			case 1:
				return 'scalar';
				break;
			default:
				return 'undef';
		}
	}

	function serializedata($typ, $val)
	{
		$rs = '';

		switch($this->xmlrpcTypes[$typ])
		{
			case 3:
				// struct
				$rs .= "<struct>\n";
				reset($val);
				while (list($key2, $val2) = each($val))
				{
					$rs .= "<member>\n<name>{$key2}</name>\n";
					$rs .= $this->serializeval($val2);
					$rs .= "</member>\n";
				}
				$rs .= '</struct>';
			break;
			case 2:
				// array
				$rs .= "<array>\n<data>\n";
				for($i=0; $i < count($val); $i++)
				{
					$rs .= $this->serializeval($val[$i]);
				}
				$rs.="</data>\n</array>\n";
				break;
			case 1:
				// others
				switch ($typ)
				{
					case $this->xmlrpcBase64:
						$rs .= "<{$typ}>" . base64_encode((string)$val) . "</{$typ}>\n";
					break;
					case $this->xmlrpcBoolean:
						$rs .= "<{$typ}>" . ((bool)$val ? '1' : '0') . "</{$typ}>\n";
					break;
					case $this->xmlrpcString:
						$rs .= "<{$typ}>" . htmlspecialchars((string)$val). "</{$typ}>\n";
					break;
					default:
						$rs .= "<{$typ}>{$val}</{$typ}>\n";
					break;
				}
			default:
			break;
		}
		return $rs;
	}

	function serialize_class()
	{
		return $this->serializeval($this);
	}

	function serializeval($o)
	{
		$ar = $o->me;
		reset($ar);

		list($typ, $val) = each($ar);
		$rs = "<value>\n".$this->serializedata($typ, $val)."</value>\n";
		return $rs;
	}

	function scalarval()
	{
		reset($this->me);
		list($a,$b) = each($this->me);
		return $b;
	}


	//-------------------------------------
	// Encode time in ISO-8601 form.
	//-------------------------------------

	// Useful for sending time in XML-RPC

	function iso8601_encode($time, $utc=0)
	{
		if ($utc == 1)
		{
			$t = strftime("%Y%m%dT%H:%i:%s", $time);
		}
		else
		{
			if (function_exists('gmstrftime'))
				$t = gmstrftime("%Y%m%dT%H:%i:%s", $time);
			else
				$t = strftime("%Y%m%dT%H:%i:%s", $time - date('Z'));
		}
		return $t;
	}

}
// END XML_RPC_Values Class

/* End of file Xmlrpc.php */
/* Location: ./system/libraries/Xmlrpc.php */