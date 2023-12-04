<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Fefree to tweak each of these messages here.
    |
    */

    "accepted"             => ":attribute debe ser aceptada.",
  	"active_url"           => ":attribute no es una URL válida.",
    "after"                => ":attribute debe ser una fecha después :date.",
    'after_or_equal'       => ':attribute debe ser una fecha posterior o igual a :date.',
    'alpha'                => ':attribute sólo puede contener letras.',
    "alpha_dash"           => ":attribute Sólo puede contener letras, números y guiones.",
  	"ascii_only"           => ":attribute Sólo puede contener letras, números y guiones.",
  	"alpha_num"            => ":attribute Sólo puede contener letras y números.",
  	"array"                => ":attribute debe ser una matriz.",
  	"before"               => ":attribute debe ser una fecha antes :date.",
    'before_or_equal'      => ':attribute debe ser una fecha anterior o igual a :date.',
    'between'              => [
      "numeric" => ":attribute debe ser entre :min y :max.",
      "file"    => ":attribute debe ser entre :min y :max kilobytes.",
      "string"  => ":attribute debe ser entre :min y :max caracteres.",
      "array"   => ":attribute debe tener entre :min y :max elementos.",
    ],
    "boolean"              => ":attribute campo debe ser verdadera o falsa",
  	"confirmed"            => "Confirmación de :attribute no coincide.",
  	"date"                 => ":attribute no es una fecha válida.",
  	"date_format"          => ":attribute no coincide con formato :format.",
  	"different"            => ":attribute y :other debe ser diferente.",
  	"digits"               => ":attribute debe ser :digits dígitos.",
  	"digits_between"       => ":attribute debe estar entre :min y :max dígitos.",
  	'dimensions'           => 'La imagen tiene dimensiones inválidas (ancho) :min_width px (alto) :min_height px.',
    'distinct'             => ':attribute campo tiene un valor duplicado.',
    "email"                => ":attribute Debe ser una dirección válida de correo electrónico.",
    "exists"               => "selected :attribute es inválido.",
    'file'                 => ':attribute debe ser un archivo.',
    "filled"               => ":attribute es requerido.",
    'gt'                   => [
        'numeric' => ':attribute debe ser mayor que :value.',
         'file' => ':attribute debe ser mayor que :value kilobytes.',
         'string' => ':attribute debe ser mayor que :value caracteres.',
         'array' => ':attribute debe tener más de :value elementos.',
    ],
    'gte'                  => [
      'numeric' => ':attribute debe ser mayor o igual que :value.',
       'file' => ':attribute debe ser mayor  o igualque :value kilobytes.',
       'string' => ':attribute debe ser mayor o igual que :value caracteres.',
       'array' => ':attribute debe tener más de :value elementos o más.',
    ],
    'image'                => ':attribute debe ser una imagen.',
    'in'                   => 'seleccionado :attribute es inválido.',
    'in_array'             => ':attribute campo no existe en :other.',
    'integer'              => ':attribute debe ser un entero.',
    'ip'                   => ':attribute debe ser una dirección IP válida.',
    'ipv4'                 => ':attribute debe ser una dirección IPv4 válida.',
    'ipv6'                 => ':attribute debe ser una dirección IPv6 válida.',
    'json'                 => ':attribute debe ser una cadena JSON válida.',
    'lt'                   => [
        'numeric' => ':attribute debe ser menor que :value.',
        'file'    => ':attribute debe ser menor que :value kilobytes.',
        'string'  => ':attribute debe ser menor que :value caracteres.',
        'array'   => ':attribute debe tener más de :value elementos.',
    ],
    'lte'                  => [
        'numeric' => ':attribute debe ser menor o igual que :value.',
        'file'    => ':attribute debe ser menor o igual que :value kilobytes.',
        'string'  => ':attribute debe ser menor o igual que :value caracteres.',
        'array'   => ':attribute no debe tener más de :value elementos.',
    ],
    'max'                  => [
        'numeric' => ':attribute puede no ser mayor que :max.',
        'file'    => ':attribute puede no ser mayor que :max kilobytes.',
        'string'  => ':attribute puede no ser mayor que :max caracteres.',
        'array'   => ':attribute puede no tener más de :max elementos.',
    ],
    'mimes'                => ':attribute debe ser un archivo de tipo: :values.',
    'mimetypes'            => ':attribute debe ser un archivo de tipo: :values.',
    'min'                  => [
        'numeric' => ':attribute al menos debe ser :min.',
        'file'    => ':attribute al menos debe ser :min kilobytes.',
        'string'  => ':attribute al menos debe ser :min caracteres.',
        'array'   => ':attribute debe tener al menos :min elementos.',
    ],
    'not_in'               => 'seleccionado :attribute es inválido.',
    'not_regex'            => ':attribute formato no es válido.',
    'numeric'              => ':attribute debe ser un número.',
    'present'              => ':attribute campo debe estar presente.',
    'regex'                => ':attribute formato no es válido.',
    'required'             => ':attribute es obligatorio.',
    'required_if'          => ':attribute es obligatorio cuando :other es :value.',
    'required_unless'      => ':attribute es obligatorio a menos que :other está en :values.',
    'required_with'        => ':attribute es obligatorio cuando :values está presente.',
    'required_with_all'    => ':attribute es obligatorio cuando :values están presentes.',
    'required_without'     => ':attribute es obligatorio cuando :values no están presentes.',
    'required_without_all' => ':attribute es obligatorio cuando ninguno de los :values están presentes.',
    'same'                 => ':attribute y :other deben coincidir.',
    'size'                 => [
        'numeric' => ':attribute debe ser :size.',
        'file'    => ':attribute debe ser :size kilobytes.',
        'string'  => ':attribute debe ser :size caracteres.',
        'array'   => ':attribute debe contener :size elementos.',
    ],
    'string'               => ':attribute debe ser una cadena.',
    'timezone'             => ':attribute debe ser una zona válida.',
    'unique'               => ':attribute ya se ha tomado.',
    'uploaded'             => ':attribute no se pudo cargar.',
    'url'                  => ':attribute formato no es válido.',
    "account_not_confirmed" => "Su cuenta no está confirmada, consulte su correo electrónico",
  	"user_suspended"        => "Su cuenta ha sido suspendida, contáctenos si es un error.",
  	"letters"              => ":attribute debe contener al menos una letra o un número",
    'video_url'          => 'URL no válida sólo admiten Youtube y Vimeo.',
    'update_max_length' => 'post no puede ser mayor que :max caracteres.',
    'update_min_length' => 'post debe ser al menos de :min caracteres.',
    'video_url_required'   => 'campo URL de video es obligatorio cuando contenido destacado es video.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
  		'agree_gdpr' => 'Casilla Estoy de acuerdo con tratamiento de datos personales.',
      'agree_terms' => 'Casilla Estoy de acuerdo con los Términos y Condiciones',
      'agree_terms_privacy' => 'Casilla Estoy de acuerdo con los Términos y Condiciones y Política de Privacidad',
  		'full_name' => 'Nombre Completo',
      'name' => 'Nombre',
  		'username'  => 'Nombre de usuario',
      'username_email' => 'Nombre de usuario o Correo electrónico',
  		'email'     => 'Correo electrónico',
  		'password'  => 'Contraseña',
  		'password_confirmation' => 'Confirmación de contraseña',
  		'website'   => 'Sitio Web',
  		'location' => 'Ubicación',
  		'countries_id' => 'País',
  		'twitter'   => 'Twitter',
  		'facebook'   => 'Facebook',
  		'google'   => 'Google',
  		'instagram'   => 'Instagram',
  		'comment' => 'Comentario',
  		'title' => 'Título',
  		'description' => 'Descripción',
      'old_password' => 'Contraseña anterior',
      'new_password' => 'Nueva contraseña',
      'email_paypal' => 'Correo PayPal',
      'email_paypal_confirmation' => 'Confirmación de correo electrónico PayPal',
      'bank_details' => 'Detalles dbanco',
      'video_url' => 'URL dVídeo',
      'categories_id' => 'Categoría',
      'story' => 'Historia',
      'image' => 'Imagen',
      'avatar' => 'Avatar',
      'message' => 'Mensaje',
      'profession' => 'Profesión',
      'thumbnail' => 'Miniatura',
      'address' => 'Dirección',
      'city' => 'Ciudad',
      'zip' => 'Postal/Código Postal',
      'payment_gateway' => 'Pasarela de pago',
      'payment_gateway_tip' => 'Pasarela de pago',
      'MAIL_FROM_ADDRESS' => 'Correo electrónico no-reply',
      'FILESYSTEM_DRIVER' => 'Disco',
      'price' => 'Precio',
      'amount' => 'Monto',
      'birthdate' => 'Fecha de nacimiento',
      'navbar_background_color' => 'Color de fondo de la barra de navegación',
    	'navbar_text_color' => 'Color dtexto de la barra de navegación',
    	'footer_background_color' => 'Color de fondo dpie de página',
    	'footer_text_color' => 'Color dtexto dpie de página',

      'AWS_ACCESS_KEY_ID' => 'Amazon Key', // Not necessary edit
      'AWS_SECRET_ACCESS_KEY' => 'Amazon Secret', // Not necessary edit
      'AWS_DEFAULT_REGION' => 'Amazon Region', // Not necessary edit
      'AWS_BUCKET' => 'Amazon Bucket', // Not necessary edit

      'DOS_ACCESS_KEY_ID' => 'DigitalOcean Key', // Not necessary edit
      'DOS_SECRET_ACCESS_KEY' => 'DigitalOcean Secret', // Not necessary edit
      'DOS_DEFAULT_REGION' => 'DigitalOcean Region', // Not necessary edit
      'DOS_BUCKET' => 'DigitalOcean Bucket', // Not necessary edit

      'WAS_ACCESS_KEY_ID' => 'Wasabi Key', // Not necessary edit
      'WAS_SECRET_ACCESS_KEY' => 'Wasabi Secret', // Not necessary edit
      'WAS_DEFAULT_REGION' => 'Wasabi Region', // Not necessary edit
      'WAS_BUCKET' => 'Wasabi Bucket', // Not necessary edit

      //===== v2.0
      'BACKBLAZE_ACCOUNT_ID' => 'Backblaze Account ID', // Not necessary edit
      'BACKBLAZE_APP_KEY' => 'Backblaze Master Application Key', // Not necessary edit
      'BACKBLAZE_BUCKET' => 'Backblaze Bucket Name', // Not necessary edit
      'BACKBLAZE_BUCKET_REGION' => 'Backblaze Bucket Region', // Not necessary edit
      'BACKBLAZE_BUCKET_ID' => 'Backblaze Bucket Endpoint', // Not necessary edit

      'VULTR_ACCESS_KEY' => 'Vultr Key', // Not necessary edit
      'VULTR_SECRET_KEY' => 'Vultr Secret', // Not necessary edit
      'VULTR_REGION' => 'Vultr Region', // Not necessary edit
      'VULTR_BUCKET' => 'Vultr Bucket', // Not necessary edit
  	],

];
