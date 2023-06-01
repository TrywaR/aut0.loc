<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title><?= $arrPage['title'] ?></title>

    <!-- jqeury  -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <script>
        // Отправка данных
        function convertFormToJSON(form) {
            const array = $(form).serializeArray(); // Encodes the set of form elements as an array of names and values.
            if ( localStorage.getItem('jwt') )
                array.push({'name':'jwt','value':localStorage.getItem('jwt')})
            
            const json = {};
            $.each(array, function() {
                json[this.name] = this.value || "";
            });
            return json;
        }
        // Проверка сессии
        var sJwt = localStorage.getItem('jwt')
        if ( sJwt ) {
            $.ajax({
                "url": "/api/",
                "type": "POST",
                "contentType": "application/json",
                "data": JSON.stringify({'table':'session','action':'continue','jwt':localStorage.getItem('jwt')}),
            }).fail(function(jqXHR, textStatus) {
                // localStorage.removeItem('jwt')
            }).done(function(oData) {
                if ( oData.reload ) location.reload()
            })
        }

        $(function(){
            // Функция выхода
            $(document).find('#user_exit').on('click', function() {
                $.ajax({
                    "url": "/api/",
                    "type": "POST",
                    "contentType": "application/json",
                    "data": JSON.stringify({
                        'table': 'authorization',
                        'action': 'logout'
                    }),
                }).done(function(){
                    localStorage.clear()
                    location.reload()
                })
                return false
            })
        })
    </script>
</head>

<body class="container">