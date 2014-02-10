var myText = 'Hello world!';
//have to enable tinymce before cloned and enable again!




function download(filename, text) {
    var pom = document.createElement('a');
    pom.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
    pom.setAttribute('download', filename);
    pom.click();
}

function openFile(textToEncode, contentType, newWindow) {
    // For window.btoa (base64) polyfills, see 
    // https://github.com/Modernizr/Modernizr/wiki/HTML5-Cross-browser-Polyfills
    var encodedText = window.btoa(textToEncode);
    var dataURL = 'data:' + contentType + ';base64,' + encodedText;
    if (newWindow) { // Not useful for application/octet-stream type
        window.open(dataURL); // To open in a new tab/window
    }
    else {
        window.location = dataURL; // To change the current page
    }
}


$(document).ready(function() {
    tinymcs();
    var template = $('#detail_1').clone();
    var forClone = $('#ops1_ipObj_1').clone();
    var ls = window.localStorage;
    $('#export').click(function() {
        text = '';
        for (key in ls)
            if (ls.getItem(key) !== '') {
                text += key + ' ' + ls.getItem(key);
            }
        openFile(text, 'text/plain', true);
    });
    $('#import').click(function() {
        for (key in ls)
            if (ls.getItem(key) !== '') {
                console.log(key + ':' + ls.getItem(key));
            }
    });
    if (!ls.getItem('opsCount')) {
        ls.setItem('opsCount', 1);
    } else {
        for (i = 2; i <= ls.getItem('opsCount'); i++) {
            loadOpsDetails(i, template);
        }
    }
//load obj
    $(":button[id$='obj']").each(function() {
        if (ls.getItem(this.id) !== null) {
            for (var x = 1; x < parseInt(ls.getItem(this.id)); x++) {
                popObj(this.id);
            }
        } else {
            ls.setItem(this.id, 1);
        }

    });
    //load param
    storeParamCount();
    function storeParamCount() {
        $(":button").each(function() {
            //console.log(this.id);
            if (ls.getItem(this.id) !== null) {
                for (var x = 1; x < parseInt(ls.getItem(this.id)); x++)
                    popParam(this.id, x);
            } else {
                ls.setItem(this.id, 1);
            }
        });
    }

    $(function() {
        $("form").sisyphus({timeout: 5});
    });



    $('#add_operation').click(function() {
        i = ls.getItem('opsCount');
        i++;
        loadOpsDetails(i, template);
        window.location = '#detail_' + i;
        ls.setItem('opsCount', i);
        storeParamCount();
    });


    $('#input_form').on('click', '.btn_clone', function() {
        id = this.id;
        count = parseInt(ls.getItem(id));
        popParam(id, count);
        ls.setItem(id, count + 1);
//        tinymcs();
    });

    $('#input_form').on('click', '.obj_btn', function() {
        popObj(this.id);
        ls.setItem(this.id, parseInt(ls.getItem(this.id)) + 1);
        //tinymcs();
    });

    function popObj(id) {
        opNum = parseInt(id.slice(5, 6));
        inOrOut = id.slice(7, 8);
        var io = (inOrOut === 'i' ? 6 : 7);
        cloneDiv = forClone.clone()
                .attr('id', 'ops' + opNum + '_' + inOrOut + 'pObj_' + (opNum + 1))
                .appendTo('#ops' + opNum + '_' + inOrOut + 'pObj');
        objNum = $('#ops' + opNum + '_' + inOrOut + 'pObj').find("input[name*='_name']").size();
        cloneDiv.find("*[name]")
                .each(function() {
                    var name = $(this).attr("name");
                    new_id = name.slice(0, 3) + opNum + '/' + io + name.slice(6, 7) + (objNum + 1) + name.slice(8);
                    this.name = new_id;
                });
        cloneDiv.find(':button.btn_clone, table.tbl_clone').each(function() {
            console.log(this.id);
            this.id = this.id.slice(0, 5) + opNum + '_' + io + '_' + (objNum + 1);
            if (this.id.slice(0, 1) === 'b' && ls.getItem(this.id) === null) {
                ls.setItem(this.id, 1);
            }
        });
        //storeParamCount();
    }

    function popParam(b_id, paramCount) {
        var t_id = '#t' + b_id.substring(1);
        $(t_id + ' tr:last').clone()
                .appendTo(t_id).find('input').val('').end()
                .find('*[name]').each(function() {
            var name = this.name;
            this.name = name.slice(0, 9) + (paramCount + 1) + name.slice(10);
            this.id = this.name;
            addTinymce();

        });
    }

    $("#clear_all").click(function() {
        ls.clear();
        for (key in ls) {
            delete ls[key];
        }
        $('form')[0].reset();
    });

    $(window).scroll(function() {
        if ($(this).scrollTop()) {
            $('#toTop').fadeIn();
            $('#add_operation').fadeIn();
        } else {
            $('#toTop').fadeOut();
            $('#add_operation').fadeOut();
        }
    });
    $("#toTop").click(function() {
        $("html, body").animate({scrollTop: 0}, 1000);
    });

    function loadOpsDetails(i, template) {
        template.clone()
                .appendTo('#input_form')
                .attr('id', 'detail_' + i).end()
                .find('span').html("Operation: " + i).end()
                .find('*[name]').each(function() {
            this.name = this.name.slice(0, 3) + i + this.name.slice(4);
            this.id = this.name;
        }).end()
                .find("div[id*='Obj']").each(function() {
            this.id = this.id.slice(0, 3) + i + this.id.slice(4);
        }).end()
                .find(':button[id^="b_"],table[id^="t_"]').each(function() {
            this.id = this.id.slice(0, 5) + i + this.id.slice(6);
        }).end();
        $('#section').append(new Option('Operation' + i + ': Input Table', 'input' + i, true, true))
                .append(new Option('Operation' + i + ': Input Example', 'input' + i + '_ex', true, true))
                .append(new Option('Operation' + i + ': Output Table', 'output' + i, true, true))
                .append(new Option('Operation' + i + ': Output Example', 'output' + i + '_ex', true, true));

    }

    function tinymcs() {
        tinymce.init({selector: 'textarea.desc',
            toolbar1: "bullist numlist italic bold",
            resize: "both",
            menubar: false
        });
    }

    function addTinymce() {
        $('.desc').each(function() {
            console.log(this.id);
            tinyMCE.execCommand("mceRemoveControl", false, this.id);
            tinyMCE.execCommand('mceAddControl', false, this.id);
        });
    }
});

//    $('#input_form').on('click', '#rm_op', function() {
//        opsCount--;
//        $('#op_summary tr').last('tr').remove();
//        divToRemove = 'operation_' + (opsCount + 1);
//        $('#' + divToRemove).remove();
//        ls.setItem('1opsCount', opsCount);
//
//    });


