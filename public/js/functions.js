/*
  Обработка событий, возникающих при вооде сообщения гостевой книги
*/
$(document).ready(() => {
    const $text = $('#text');
    const $previewMessage = $('.preview-message');
    const $previewButton = $('#preview-button');
    const $pictures = $('#pictures');
    const $filepath = $('#filepath');

    $text.on('focusin', () => {
        $previewMessage.show().animate({opacity:1});
        $('#preview-button').val('скрыть');
    });

    $text.on('focusout', () => {
        $previewMessage.hide().animate({opacity:0});
        $('#preview-button').val('отобразить');
    });

    $text.on('change keyup paste', () => {
        $textMessage = $text.val()
            .replace(/\[italic\](.+?)\[\/italic\]/g, '<i>$1</i>')
            .replace(/\[code\](.+?)\[\/code\]/g, '<code>$1</code>')
            .replace(/\[strike\](.+?)\[\/strike\]/g, '<strike>$1</strike>')
            .replace(/\[strong\](.+?)\[\/strong\]/g, '<strong>$1</strong>')
            .replace(/\[a\s*(\w+\s*=\s*(?:".*?"|'.*?'))\s*\](.*?)\[\s*\/a\s*\]/g, '<a $1>$2</a>');
        $previewMessage.html($textMessage);
    });

    $previewButton.on('click', () => {
        $textMessage = $text.val()
            .replace(/\[italic\](.+?)\[\/italic\]/g, '<i>$1</i>')
            .replace(/\[code\](.+?)\[\/code\]/g, '<code>$1</code>')
            .replace(/\[strike\](.+?)\[\/strike\]/g, '<strike>$1</strike>')
            .replace(/\[strong\](.+?)\[\/strong\]/g, '<strong>$1</strong>')
            .replace(/\[a\s*(\w+\s*=\s*(?:".*?"|'.*?'))\s*\](.*?)\[\s*\/a\s*\]/g, '<a $1>$2</a>');
        $previewMessage.html($textMessage);
        if ($previewMessage.css('opacity') == 0) {
            $previewMessage.show().animate({opacity:1});
            $('#preview-button').val('скрыть');
        } else {
            $previewMessage.hide().animate({opacity:0});
            $('#preview-button').val('отобразить');
        }
    });

    $pictures.on('change', () => {
        const $pictures = $("#pictures").get(0).files[0];
        if (!($pictures.type == "image/jpeg") && !($pictures.type == "image/png") && !($pictures.type == "image/gif")){
            event.target.value='';
            $("#fileinfo-img").html("<span style='color: red; font-size: 10px;'>Допустимые форматы файлов jpg, gif, png</span> ");
        } else {
            $("#fileinfo-img").html("<span id='fileinfo-img'></span>");
        }
    });

    $filepath.on('change', () => {
        var $filepath = $("#filepath").get(0).files[0];
        if ($filepath.type != "text/plain"){
            event.target.value='';
            $("#fileinfo-file").html("<span style='color: red; font-size: 10px;'>Допустимый формат файла txt.</span> ");
        } else {
            $("#fileinfo-file").html("<span id='fileinfo-file'></span>");
        }
        if ($filepath.size > 100*1024){
            event.target.value='';
            $("#fileinfo-file").html($("#fileinfo-file").val() + " <span style='color: red; font-size: 10px;'>Допустимый размер файла 100 Кб</span> ");
        } else {
            $("#fileinfo-file").html("<span id='fileinfo-file'></span>");
        }
    });
});

/*
  отображение выборки сообщений постранично
  (страница, количество сообщений на странице)
*/
function viewMessages(rank, count)
{
    for (i=1; i<=count; i++) {
        if (i != rank) {
            document.getElementById('visability'+i).className = 'displayNone';
            document.getElementById('number'+i).style.color = 'black';
            document.getElementById('number_foot'+i).style.color = 'black';
        } else {
            document.getElementById('visability'+i).className = '';
            document.getElementById('number'+i).style.color = '#006699';
            document.getElementById('number_foot'+i).style.color = '#006699';
        }
    }
}

/*
  форматирование текста сообщения тэгами
  (тэг)
*/
function formatTextArea(tag)
{
    var field = document.getElementById('text');
    var value  = field.value;
    var selected = value.substring(field.selectionStart, field.selectionEnd);
    var before = value.substring(0, field.selectionStart);
    var after = value.substring(field.selectionEnd, field.length);
    if (tag == 'a') {
        field.value = before + '[' + tag + ' href = \"\" title = \"\"]' + selected + '[/'+ tag +']' + after;
        //field.value = '${before} [${tag} href = "" title = ""] ${selected} [/${tag}]';
    } else {
        field.value = before + '[' + tag + ']' + selected + '[/'+ tag +']' + after;
        //field.value = '${before} [${tag}] ${selected} [/${tag}]';
    }
}
