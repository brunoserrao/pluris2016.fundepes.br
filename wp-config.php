<?php
/** 
 * As configurações básicas do WordPress.
 *
 * Esse arquivo contém as seguintes configurações: configurações de MySQL, Prefixo de Tabelas,
 * Chaves secretas, Idioma do WordPress, e ABSPATH. Você pode encontrar mais informações
 * visitando {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. Você pode obter as configurações de MySQL de seu servidor de hospedagem.
 *
 * Esse arquivo é usado pelo script ed criação wp-config.php durante a
 * instalação. Você não precisa usar o site, você pode apenas salvar esse arquivo
 * como "wp-config.php" e preencher os valores.
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar essas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define('DB_NAME', 'pluris20_banco');

/** Usuário do banco de dados MySQL */
define('DB_USER', 'pluris20_usuario');

/** Senha do banco de dados MySQL */
define('DB_PASSWORD', 'V$f82k4L$x0A');

/** nome do host do MySQL */
define('DB_HOST', 'localhost');

/** Conjunto de caracteres do banco de dados a ser usado na criação das tabelas. */
define('DB_CHARSET', 'utf8');

/** O tipo de collate do banco de dados. Não altere isso se tiver dúvidas. */
define('DB_COLLATE', '');

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Você pode alterá-las a qualquer momento para desvalidar quaisquer cookies existentes. Isto irá forçar todos os usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'nBw(w|~#8|9S{A=Hc3WtjHDszAM&01MJLm0g*z){>3UeAez.|628&#dH_6[#s6ky');
define('SECURE_AUTH_KEY',  'VY0u|*::7bK0O4w;U:pr2nO@N+pY=KI[N:z9f5*+1 b{vfFY >CsC7D5gAe)ViU`');
define('LOGGED_IN_KEY',    'H_1q- qP~+UKmcf#>zmkF~OVA^T|>z>KiUQ-6)+MPur)};u~e@]ke^!@(]vAh,j2');
define('NONCE_KEY',        '{$_#^Ux5YvlcL(@SSR=wQ[_NHYX:_@e:(^x?XD4f$XZIfz1T#6eJyfZ7%SZ`0Wy{');
define('AUTH_SALT',        '>3@o]&%y4m|GJiOW?*-L<5b1WxcJOdoHnN9d(Lcw9CgM-V`5A>^}s_p>X@w365_C');
define('SECURE_AUTH_SALT', 'p$hE+OH/6E|z>|v@gbTDEXz:;I(9{Ybj:%fpHs|YsLfpo[Un-gi-.z1~O>]=ZIa)');
define('LOGGED_IN_SALT',   ')3X8G#.y |B|:*HZ6r8~Fr;Pv)x.&/TO R&h3@+;so,Y#m3~I:q2U[f+<XkA=Qrs');
define('NONCE_SALT',       'I@@fe$vf]jpqX)QLzv*6,6fu$6ov2H7w8^%dM-jG1!oFL<dx[h?Ho|w`f/Ase28D');

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der para cada um um único
 * prefixo. Somente números, letras e sublinhados!
 */
$table_prefix  = 'congresso_';


/**
 * Para desenvolvedores: Modo debugging WordPress.
 *
 * altere isto para true para ativar a exibição de avisos durante o desenvolvimento.
 * é altamente recomendável que os desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 */
define('WP_DEBUG', false);

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
	
/** Configura as variáveis do WordPress e arquivos inclusos. */
require_once(ABSPATH . 'wp-settings.php');
