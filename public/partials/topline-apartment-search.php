<section class="row-fluid content-criteria">

    <div class="span3 criteria">
        <p class="title">When are you moving? <i class="icon-calendar"></i></p>

        <section class="criteria-details" style="display: none;">
            <input id="startdate" name="startdate" min="2012-01-01" max="2015-01-01" type="date">
        </section>
    </div>

    <div class="span3 criteria">
        <p class="title">Name your price range. <i class="icon-money"></i></p>

        <section class="criteria-details" style="display: none;">
            <em>$800-$2500</em>
            <input id="slider1" type="range" min="800" max="2500" value="1200" step="50" onchange="printValue('slider1','rangeValue1')">
                <div class="price-select">
                    <p>$</p>
                    <input id="rangeValue1" type="text">
                </div>
        </section>
    </div>

    <div class="span3 criteria">
        <p class="title">Unit Details <i class="icon-th"></i></p>

        <section class="criteria-details beds" style="display: none;">
            <div>
                <input type="number" min="1" max="4" step="1" value="2" name="bedrooms"><span>Bedrooms</span>
            </div>
            <div>
                <input type="number" min="1" max="4" step="0.5" value="1.5" name="bathrooms"><span>Bathrooms</span>
            </div>
        </section>

    </div>

    <div class="span3 criteria">
        <input type="submit" value="Update Options" class="button">
    </div>

</section>