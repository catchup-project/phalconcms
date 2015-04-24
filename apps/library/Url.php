<?php
/**

$router->add('/admin(/:controller((/{id:[0-9]+})?/:action)?)?', [
'module'     => 'backend',
'controller' => 2,
'action'     => 6,
'id'         => 5,
])->setName('default');


$this->_di->set('url', function () use ($config)
{
return new \Library\URL();
});


<a href="<?= $this->url->route('default', ['controller'=>'users']); ?>"></a> // => /admin/users
<a href="<?= $this->url->route('default', ['controller'=>'users', 'action'=>'list']); ?>"></a> // => /admin/users/list
<a href="<?= $this->url->route('default', ['controller'=>'users', 'action'=>'edit', 'id'=>1]); ?>"></a> // => /admin/users/1/edit



 **/

namespace Library;

class Url extends \Phalcon\Mvc\Url
{

    const REGEX_KEY     = ':([a-zA-Z0-9_]++)';
    const REGEX_SEGMENT = '[^/.,;?\n]++';
    const REGEX_ESCAPE  = '[.\\+*?^\\${}=!|]';

    public function base($protocol = NULL, $index = FALSE)
    {
        $base_url = trim($this->getDI()->get('url')->getBaseUri(), '/');

        if (TRUE === $protocol)
        {
            $protocol = $this->getDI()->get('request')->getScheme();
        }

        if (!$protocol)
        {
            $protocol = parse_url($base_url, PHP_URL_SCHEME);
        }

        $index_file = $this->getDI()->get('config')->application->index_file;

        if (TRUE === $index AND !empty($index_file))
        {
            $base_url .= $index_file . '/';
        }

        if (is_string($protocol))
        {
            if ($port = parse_url($base_url, PHP_URL_PORT))
            {
                $port = ':' . $port;
            }

            if ($domain = parse_url($base_url, PHP_URL_HOST))
            {
                $base_url = parse_url($base_url, PHP_URL_PATH);
            }
            else
            {
                $domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
            }

            $base_url = $protocol . '://' . $domain . $port . $base_url;
        }

        return rtrim($base_url, '/');
    }

    public function site($uri = '', $protocol = NULL, $index = TRUE)
    {
        $path = preg_replace('~^[-a-z0-9+.]++://[^/]++/?~', '', trim($uri, '/'));

        if (preg_match('/[^\x00-\x7F]/S', $path))
        {
            $path = preg_replace_callback(
                '~([^/]+)~',
                function ($matches)
                {
                    return rawurlencode($matches[0]);
                },
                $path);
        }

        return $this->base($protocol, $index) . (!empty($path) ? '/' . $path : '');
    }

    public function route($route, array $params = [])
    {
        $current = $this->_route($route);
        $uri = $current['pattern'];

        $router = $this->getDI()->getShared('router');
        $defaults = array(
            'namespace'		=> null, //$router->getNamespaceName(),
            'module'		=> null, //$router->getModuleName(),
            'controller'	=> null, //$router->getControllerName(),
            'action'		=> null, //$router->getActionName(),
        );

        $provided_optional = FALSE;

        foreach ($params as $key => $arr)
        {
            if (is_array($arr))
            {
                $param = [];
                foreach ($arr as $k => $v)
                {
                    $param[] = $k.'/'.$v;
                }

                if (!empty($param))
                {
                    $params[$key] = implode('/', $param);
                }
            }
        }

        while (preg_match('~\([^()]++\)(\?)?~', $uri, $match))
        {
            $search = $match[0];
            $replace = substr($match[0], 1, empty($match[1]) ? -1 : -2);

            $optional = !empty($match[1]);

            while (preg_match('~'.URL::REGEX_KEY.'~', $replace, $match))
            {
                list($key, $param) = $match;

                $default = isset($defaults[$param]) ? $defaults[$param] : '';

                if (isset($params[$param]) && $params[$param] != $default)
                {
                    $provided_optional = true;
                    $replace = str_replace($key, $params[$param], $replace);
                }
                elseif ($provided_optional && $optional)
                {
                    if (isset($defaults[$param]))
                    {
                        $replace = str_replace($key, $default, $replace);
                    }
                    else
                    {
                        $replace = str_replace($key, '', $replace);
                    }
                }
                else
                {
                    $replace = '';
                    break;
                }
            }

            $uri = str_replace($search, $replace, $uri);
        }

        while (preg_match('~'.URL::REGEX_KEY.'~', $uri, $match))
        {
            list($key, $param) = $match;

            if ( ! isset($params[$param]))
            {
                if (isset($defaults[$param]))
                {
                    $params[$param] = $defaults[$param];
                }
                else
                {
                    throw new \Phalcon\Exception('Required route parameter not passed: '.$param);
                }
            }

            $uri = str_replace($key, $params[$param], $uri);
        }

        return preg_replace('~//+~', '/', rtrim($uri, '/'));
    }

    protected function _route($route)
    {
        static $_cache = [];

        if (!isset($_cache[$route]))
        {
            $current_route = $this->getDI()->getShared('router')->getRouteByName($route);
            if (empty($current_route))
            {
                throw new \Phalcon\Exception('Routes not found');
            }

            $uri = $current_route->getPattern();
            if (empty($uri) || !is_string($uri))
            {
                throw new \Phalcon\Exception('Error route'.$uri);
            }

            $params = [];
            while (preg_match('~\{([a-zA-Z0-9_]++):([^}]++)\}~uD', $uri, $match))
            {
                $params[$match[1]] = $match[2];
                $uri = str_replace('{'.$match[1].':'.$match[2].'}', ':'.$match[1], $uri);
            }

            $expression = $uri;

            if (false !== strpos($expression, '('))
            {
                $offset = null;
                for ($count=1; (false !== ($pos = strpos($expression, '(', $offset))); $count++)
                {
                    if ('?P' != substr($expression, $pos+1, 2))
                    {
                        $expression = substr_replace($expression, '(?:', $pos, 1);
                    }
                    $offset = $pos + 1;
                }
            }

            while (preg_match('~:([a-zA-Z0-9_]++)~uD', $expression, $match))
            {
                $replace = isset($params[$match[1]]) ? $params[$match[1]] : ('params' != $match[1] ? URL::REGEX_SEGMENT : '.*');
                $expression = str_replace(':'.$match[1], '(?P<'.$match[1].'>'.$replace.')', $expression);
            }

            $sections = $this->_split($expression);
            if (!empty($sections))
            {
                $paths = $current_route->getPaths();
                if (!empty($paths))
                {
                    $brackets = [];
                    $offset = null;
                    for ($count=1; (false !== ($pos = strpos($expression, '(', $offset))); $count++)
                    {
                        if ('?:' == substr($expression, $pos+1, 2))
                        {
                            $brackets[$count] = $pos;
                        }
                        $offset = $pos + 1;
                    }

                    foreach ($paths as $key => $id)
                    {
                        if (isset($sections[$id]) && isset($brackets[$id]))
                        {
                            $len = strlen('(?:'.$sections[$id].')');
                            $expression = substr_replace($expression, '(?P<'.$key.'>'.$sections[$id].')', $brackets[$id], $len);
                        }
                    }
                }
            }

            $pattern = $expression;
            $sections = $this->_split($pattern);
            if (!empty($sections))
            {
                $paths = $current_route->getPaths();
                if (!empty($paths))
                {
                    $brackets = [];
                    $offset = null;
                    for ($count=1; (false !== ($pos = strpos($pattern, '(', $offset))); $count++)
                    {
                        if ('?P' == substr($pattern, $pos+1, 2))
                        {
                            $brackets[$count] = $pos;
                        }
                        $offset = $pos + 1;
                    }

                    foreach ($paths as $key => $id)
                    {
                        if (isset($sections[$id]) && isset($brackets[$id]))
                        {
                            $pattern = str_replace('(?P'.$sections[$id].')', ':'.$key, $pattern);
                        }
                    }
                }
            }

            $_cache[$route] = array(
                'uri'		=> $uri,
                'regex'		=> '~^'.$expression.'$~uD',
                'pattern'	=> str_replace('(?:', '(', $pattern),
            );
        }

        return $_cache[$route];
    }

    protected function _split($string, $level = 1)
    {
        $result = [];
        if (preg_match_all('~\((([^()]*|(?R))*)\)(\?)?~isuD', $string, $matches))
        {
            foreach ($matches[1] as $m)
            {
                $m = preg_replace('~^\?(:|P)~isuD', '', $m);
                $result[$level++] = $m;
                $arr = $this->_split($m);
                if (!empty($arr))
                {
                    foreach ($arr as $item)
                    {
                        $result[$level++] = preg_replace('~^\?(:|P)~isuD', '', $item);;
                    }
                }
            }
        }
        return $result;
    }

}