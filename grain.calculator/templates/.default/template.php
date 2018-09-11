<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

//debmes($arResult);
?>

<div class="row caalc">
    <div class="columns large-12 medium-12 small-12">
        <h1><?$APPLICATION->ShowTitle()?></h1>
        <div class="calc_body_grain">
            <div class="columns large-6 medium-6 small-12">
                <p>Выберите продукт:</p>
                <select class="calc_select_grain" id="grains" placeholder="Выберите продукт">
                    <option value="0">Выбрать продукт</option>
                    <?foreach($arResult['PRODUCTS'] as $code => $product):?>
                        <option value="<?=$code?>"><?=$product['NAME']?></option>
                    <?endforeach?>
                </select>
            </div>
            <div class="columns large-6 medium-6 small-12">
                <p>Выберите категорию продукта:</p>
                <select class="calc_select_grain" id="grain_cat" placeholder="Выберите категорию">
                    <option value="0">Выбрать категорию</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            <div class="columns large-6 medium-6 small-12">
                <p>Выберите область (необязательно)</p>
                <select class="calc_select_grain" id="region" placeholder="Выберите область (необязательно)">
                    <option value="zero">Все области</option>
                    <?foreach($arResult['ELEVATORS'] as $place => $arElevators):?>
                        <option value="<?=$place?>"><?=$place?></option>
                    <?endforeach?>
                </select>
            </div>
            <div class="columns large-6 medium-6 small-12">
                <p>Выберите элеватор:</p>
                <select class="calc_select_grain" id="elevators" placeholder="Выберите элеватор">
                    <option value="0" id="elev_default">Выбрать элеватор</option>
                    <?foreach($arResult['ELEVATORS'] as $place => $arElevators):?>
                        <?foreach($arElevators as $code => $elevator):?>
                            <option value="<?=$code?>" class="<?=implode(' ', $elevator['PRODUCT_CODE'])?>" data-region="<?=$place?>"><?=$elevator['NAME']?></option>
                        <?endforeach?>
                    <?endforeach?>
                </select>
            </div>

            <div class="columns large-6 medium-6 small-12">
                <p>Введите срок свопа в днях:</p>
                <input type="text" id="duration" class="calc_input_grain" required="required" placeholder="3-90 дней" />
            </div>
            <div class="columns large-6 medium-6 small-12">
                <p>Введите объем товара в тоннах:</p>
                <input type="text" id="volume" class="calc_input_grain" required="required" />
            </div>
        </div>
        <div style="margin-top: 20px;">
            <center>
                <a href="javascript:void(0)" class="card-list-item-issue card__btn" id="make_calc_grain">Произвести расчет</a>
            </center>
        </div>
    </div>
    <div class="columns large-12 medium-12 small-12" style="margin-top: 40px;">
        <div id="errormes">

        </div>
        <div id="resultblock" style="display:none;">
            <div class="columns large-6 medium-6 small-12">
                <p>Общая сумма:</p>
                <input type="text" id="result" class="calc_input_grain_r" disabled />
            </div>
            <div class="columns large-6 medium-6 small-12">
                <p>Комиссия за хранение:</p>
                <input type="text" id="store_coeff" class="calc_input_grain_r" disabled />
            </div>

            <div class="columns large-6 medium-6 small-12">
                <p>Комиссия за учет:</p>
                <input type="text" id="accounting" class="calc_input_grain_r" disabled />
            </div>
            <div class="columns large-6 medium-6 small-12">
                <p>Комиссия биржи и клиринга:</p>
                <input type="text" id="clearing" class="calc_input_grain_r" disabled />
            </div>

            <div class="columns large-6 medium-6 small-12">
                <p>Комиссия брокера:</p>
                <input type="text" id="broker" class="calc_input_grain_r" disabled />
            </div>
            <div class="columns large-6 medium-6 small-12">
                <p>Своп-разница с учетом хранения:</p>
                <input type="text" id="swap" class="calc_input_grain_r" disabled />
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function(){

        var filterObject = {prod: 0, obl: 'zero'};

        function inputValToInt(inputVal){
            if(inputVal){
                inputVal = inputVal.replace(new RegExp('[^0-9]', 'g'), '');
                return parseInt(inputVal);
            }
            return 0;
        }

        $(document).on('keydown', '.calc_input_grain', function(e){
            e = e || event;
            var doCheck = true;
            if (e.ctrlKey || e.altKey || e.metaKey || e.which == 8 || e.which == 46){
                doCheck = false;
            }
            var chr = parseInt(e.key);
            if((isNaN(chr) || chr > 9) && doCheck) {
                return false;
            }
        });


        var elevators = '<?=$arResult['JSON_ELEVATORS']?>';
        try{
            elevators = JSON.parse(elevators);
            //console.log(elevators);
        }catch(e){
            //console.warn(e);
        }

        function changeOptionsVisibility(){
            var elevs = $('#elevators option');
            var vl = filterObject.obl;
            elevs.hide();
            if(vl == 'zero'){
                elevs.show();
            }else{
                elevs.each(function(){
                    if($(this).attr('data-region') == vl){
                        $(this).show();
                    }
                });
            }
            var prod = filterObject.prod;
            if(prod == 0){
                //  do nothing
            }else{
                elevs.each(function(){
                   if(!$(this).hasClass(prod)){
                       $(this).hide();
                   }
                });
            }

            elevs.attr("selected", false);
            $('#elev_default').attr('selected', 'selected').show();
        }

        $('#region').change(function(){
            filterObject.obl = $(this).val();
            changeOptionsVisibility();
        });

        $('#grains').change(function(){
            filterObject.prod = $(this).val();
            changeOptionsVisibility();
        });



        $('#make_calc_grain').click(function(){
            var prod = $('#grains').val();
            var cat = $('#grain_cat').val();
            var elev = $('#elevators').val();
            var dura = inputValToInt($('#duration').val());
            var tons = inputValToInt($('#volume').val());
            if(prod == 0){
                alert('Выберите продукт!');
                return false;
            }
            if(cat == 0){
                alert('Выберите категорию!');
                return false;
            }
            if(elev == 0){
                alert('Выберите элеватор!');
                return false;
            }
            if(dura < 3 || dura > 90){
                alert('Срок свопа должен быть между 3 и 90 днями!');
                return false;
            }
            if(tons == 0){
                alert('Укажите объем товара!');
                return false;
            }
            //  Отправляем данные на сервер
            var data = {
                action: 'make_calc',
                prod: prod,
                category: cat,
                elevator: elev,
                duration: dura,
                volume: tons
            };

            $.ajax({
                type: 'POST',
                data: data,
                success: function(res){
                    try{
                        res = JSON.parse(res);
                        //console.log(res);
                        if(res.error && res.error.error){
                            $('#errormes').text(res.error.error_text);
                            $('#resultblock').hide();
                            return;
                        }else{
                            $('#errormes').text('');
                        }

                        $('#result').val(res.result + ' р.');
                        $('#store_coeff').val(res.store_coeff + ' р.');
                        $('#accounting').val(res.accounting + ' р.');
                        $('#clearing').val(res.clearing + ' р.');
                        $('#broker').val(res.broker + ' р.');
                        $('#swap').val(res.swap + ' р.');
                        $('#resultblock').show();
                    }catch(e){
                        //console.warn(e);
                        $('#errormes').text('Техническая ошибка');
                    }
                },
                error: function(a, b, c){
                    //console.error([a,b,c]);
                    $('#errormes').text('Техническая ошибка');
                }
            });
        });


    });
</script>

<style>
    .calc_body_grain, #resultblock{
        flex-wrap: wrap;
        display: flex;
    }
    .calc_input_grain{
        width: 100%;
    }
    .caalc{
        margin-bottom: 40px;
    }
    #errormes{
        color: #dd4444;
        font-weight: bold;
        font-size: 24px;
    }
</style>