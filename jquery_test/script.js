function sleep(ms)
{
    return new Promise(resolve => setTimeout(resolve, ms));
}
function show_toggle_self_flex(elem_name)
{
    $(elem_name).click(function ()
    {
        $(elem_name).hide(200);
        $(elem_name).show(500);
    });
}

function fade_toggle_self(elem_name)
{
    $(elem_name).click(function ()
    {
        $(elem_name).fadeTo(200, 0.25);
        $(elem_name).fadeTo(200, 1);
    }
    );
}

function slide_self(elem_name)
{
    $(elem_name).click(function ()
    {
        $(elem_name).slideUp(200);
        $(elem_name).slideDown(500);
    }
    );
}

function strange_anim_self(elem_name)
{
    $(elem_name).click(function ()
    {
        $(elem_name).animate({marginLeft: "100px"}, 300);
        $(elem_name).animate({marginRight: "100px"}, 300);
        $(elem_name).animate({marginTop: "100px"}, 300);
        $(elem_name).animate({
            borderRadius: "100px",
            margin: "10px"
        }, 1000);
        $(elem_name).animate({borderRadius: "10px"}, 2000);
    }
    );
}

function stop_button(elem_name)
{
    $(elem_name).click(function ()
    {
        $("*").stop();
    }
    );
}

function add_button(elem_name)
{
    $(elem_name).click(function ()
    {
        $("main").append("<p class='add_p'>Приветики</p>");
    }
    );
}

function del_button(elem_name)
{
    $(elem_name).click(function ()
    {
      $(".add_p").remove();
    }
    );
}

function toggle_class(elem_name)
{
    $(elem_name).hover(function ()
    {
        $(elem_name).toggleClass("white");
    }
    );
}

function toggle_css(elem_name)
{
    $(elem_name).click(function ()
    {
        $(elem_name).css("background-color", "black");
    }
    );
}

function find_parent(elem_name)
{
    return $(elem_name).parent();
}

function load_txt(elem_name)
{
    $(elem_name).click(function ()
    {
        $(elem_name).load("ajax_test.txt");
    }
    );
}

function n1(elem_name)
{
    $(elem_name).on("keyup", function() {
        let value = $(elem_name).val().toLowerCase();
        $(elem_name + " tr").filter(function()
        {
            $(elem_name).toggle(400, $(elem_name).text().toLowerCase().indexOf(value) > -1)
        }
        );
    }
    );
}

function main()
{
    show_toggle_self_flex("#d1");
    fade_toggle_self("#d2");
    slide_self("#d3");
    strange_anim_self("#d4");
    stop_button("#stop_button");
    add_button("#add_button");
    del_button("#del_button");
    toggle_class("#bye");
    toggle_css("header");
    console.log(find_parent(".aside-link"));
    load_txt("#h1_ajax")
}

$(document).ready(main);