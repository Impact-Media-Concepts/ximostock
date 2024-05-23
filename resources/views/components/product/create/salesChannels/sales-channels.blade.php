<!-- sets url path for img -->
<?php
    $app_url = env('VITE_APP_URL');
?>

<div class="bg-white rounded-t-lg basic:h-[38rem] hd:h-[50rem] uhd:h-[57rem] create-container-border">
    <div class="h-[4.56rem] uhd:w-[138rem] rounded-t-lg" style="border: 1px solid #F0F0F0;">
        <div class=" ml-[1.56rem] mt-[0.6rem]">
            <div>
                <p class="text-[18px] font-bold text-[#717171]">
                    Verkoopkanalen
                </p>
            </div>
            <div>
                <p class="text-[14px] text-[#717171]">
                    Hier kan je verkoopkanalen koppelen
                </p>
            </div>
        </div>
    </div>
    
    <div class="flex justify-center w-full">
        <div class="mt-[2rem] mb-[0.5rem] basic:h-[31.5rem] basic:w-[67rem] hd:h-[43.5rem] uhd:h-[50rem] hd:w-[94rem] uhd:w-[134rem]" style="border: 1px solid #f0f0f0; border-radius: 10px;">
            <div class="h-[2.5rem] bg-[#3DABD5] rounded-t-lg flex justify-start items-center pl-4 text-white">
                <p>Verkoopkanalen</p>
            </div>
            <div>
                <div class="flex items-center pb-[1rem] pt-[0.5rem]" style="border: 1px solid #f0f0f0">
                    <div class="flex items-center justify-start">
                        <div class="flex items-center justify-center flex-col gap-[0.5rem]">
                            <div class="w-full flex justify-start">
                                <p class="flex ml-[1.25rem]">Verkoopkanaal titel:</p>
                            </div>
                            
                            <div class="flex mt-[0.08rem] items-center">
                                <button type="button" class="relative flex items-center left-10">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                        stroke="#D3D3D3" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                    </svg>
                                </button>
                                
                                <input id="salesChannelSearchInput" class="uhd:w-[43.43rem] h-[2.5rem] rounded-md pl-[3rem] pt-[0.1rem] pr-[1rem] text-[#717171] header-search"
                                    style="font-size: 16px; border:1px solid #D3D3D3;" type="text" placeholder="Zoeken..."
                                    autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center mb-[1rem] h-[3.56rem]"  style="border: 1px solid #f0f0f0">
                    <div class="flex justify-start ml-[1.5rem] gap-[2rem]">
                        <input id="selectAllSalesChannels" type="checkbox" class="slideon slideon-auto">
                        <p>
                            selecteer alle verkoopkanalen
                        </p>
                    </div>
                </div>
                
                <div class="basic:max-h-[17.5rem] hd:max-h-[30.3rem] uhd:max-h-[36rem] overflow-y-auto pb-[1rem]" id="salesChannelList">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let salesChannelsData = [
        @foreach ( $salesChannels as $salesChannel)
            {
                id:{{$salesChannel->id}},
                name: "{{ $salesChannel->name }}"
            },
        @endforeach
    ];
    const create_prod_sales_chan_app_url = {!! json_encode($app_url) !!};
</script>
<script type="text/javascript" src="{{ asset('./assets/js/product/create-product-sales-channels.js') }}"></script>
