@props(['products'])

<style>
    /* scrollbar */
    .product-scrollbar::-webkit-scrollbar {
        width: 0px;
    }

    ::-webkit-scrollbar {
        width: 20px;
    }

    ::-webkit-scrollbar-track {
        background: #fff;
        border: 1px solid #DBDBDB;
        border-radius: 15px;
        z-index: -1 !important;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #DBDBDB;
        border: 6px solid transparent;
        background-clip: content-box;
        border-radius: 15px;

        z-index: -1 !important;
    }

    ::-webkit-scrollbar-button:single-button {
        display: block;
        border-style: solid;
        height: 13px;
    }

    /* Up */
    ::-webkit-scrollbar-button:single-button:vertical:decrement {
        border-width: 10px 10px 10px 10px;
        border-color: transparent transparent #000000 transparent;
    }

    ::-webkit-scrollbar-button:single-button:vertical:decrement:hover {
        border-color: transparent transparent #524d4d transparent;
    }

    /* Down */
    ::-webkit-scrollbar-button:single-button:vertical:increment {
        border-width: 10px 10px 10px 10px;
        border-color: #000000 transparent transparent transparent;
    }

    ::-webkit-scrollbar-button:vertical:single-button:increment:hover {
        border-color: #524d4d transparent transparent transparent;
    }
</style>

<div class="relative flex items-center bg-white">
    <form class="relative" action="{{ route('products.bulkDelete') }}" method="POST">
        @csrf
        <ul class="w-[78.81rem] overflow-y-auto overflow-x-hidden max-h-[43.75rem] product-scrollbar" style=""
            id="container">
            @foreach ($products as $product)
                <x-product.product-item :product="$product" />
            @endforeach
        </ul>
        {{-- <button class="flex" type="submit">Delete Selected Products</button> --}}
    </form>
</div>
<script>
    $.fn.dragCheck = function(selector) {
        if (selector === false)
            return this.find("*")
                .andSelf()
                .add(document)
                .unbind(".dc")
                .removeClass("dc-selected")
                .filter(":has(:checkbox)")
                .css({
                    MozUserSelect: "",
                    cursor: ""
                });
        else
            return this.each(function() {
                // if a checkbox is clicked this will be set to
                // it's checked state (true||false), otherwise null
                var mdown = null;

                // get the specified container, or children if not specified
                $(this)
                    .find(selector || "> *")
                    .filter(":has(:checkbox)")
                    .each(function() {
                        // highlight all already checked boxes
                        if ($(this).find(":checkbox:checked").length)
                            $(this).addClass("dc-selected");
                    })
                    .bind("mouseover.dc", function() {
                        // if a checkbox was clicked and mouse button being held down
                        if (mdown != null) {
                            // set this container's checkbox to the
                            // same state as the one first clicked
                            $(this).find(":checkbox")[0].checked = mdown;
                            // add the highlight class
                            $(this).toggleClass("dc-selected", mdown);
                        }
                    })
                    .bind("mousedown.dc", function(e) {
                        // find this container's checkbox
                        var t = e.target;
                        if (!$(t).is(":checkbox")) t = $(this).find(":checkbox")[0];

                        // switch its state (click event will be canceled later)
                        t.checked = !t.checked;
                        // set the value to which other hovered
                        // checkboxes will be set while the mouse is down
                        mdown = t.checked;

                        // highlight this one according to its state
                        $(this).toggleClass("dc-selected", mdown);
                    })

                    // avoid text selection
                    .bind("selectstart.dc", function() {
                        return false;
                    })
                    .css({
                        MozUserSelect: "none",
                        cursor: "default",
                    })

                    // cancel the click event on the checkboxes because
                    // we already switched its checked state on mousedown
                    .find(":checkbox")
                    .bind("click.dc", function() {
                        return false;
                    });

                // clear the mdown var if the mouse button is released
                // anywhere on the page
                $(document).bind("mouseup.dc", function() {
                    mdown = null;
                });
            });
    };

    $("#container").dragCheck(".flex");
</script>
