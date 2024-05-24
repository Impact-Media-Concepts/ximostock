document.addEventListener("DOMContentLoaded", (event) => {
    //slider
    let slideon = new Slideon();
    slideon.load();
    
    // check and uncheck all
    const selectAllSalesCheckbox = document.getElementById("selectAllSalesChannels");
    
    selectAllSalesCheckbox.addEventListener("change", function() {
        // every sales-channels-item.blade with that id
        const selectSalesItems = document.querySelectorAll("[id^=selectSalesItem_]");
        
        selectSalesItems.forEach(function(checkbox) {
            checkbox.checked = selectAllSalesCheckbox.checked;
        });
    });
    
    function renderSalesChannels() {
        const salesChannelList = document.getElementById('salesChannelList');
        
        salesChannelList.innerHTML = '';
        //salesChannelsData is defined in component
        salesChannelsData.forEach(salesChannel => {
            const label = document.createElement('label');
            const input = document.createElement('input');
            const span = document.createElement('span');
            
            label.classList.add("slideon");
            
            span.classList.add("slideon-slider");
            
            input.id = `selectSalesItem_${salesChannel.id}`;
            input.type = 'checkbox';
            input.classList.add('slideon', 'slideon-auto');
            input.name = 'sales_channel_ids[]';
            input.value = `${salesChannel.id}`;
            
            const divContainer = document.createElement('div');
            divContainer.classList.add('flex', 'justify-center', 'py-[0.5rem]');
            divContainer.id = `sales_div_${salesChannel.id}`;
            
            const innerDiv = document.createElement('div');
            innerDiv.classList.add('w-[63rem]', 'h-[3.68rem]', 'flex', 'items-center', 'bg-[#F8F8F8]', 'rounded-md');
            innerDiv.style.border = '1px solid #F0F0F0';
            
            const imgDiv = document.createElement('div');
            imgDiv.classList.add('px-[1.25rem]');
            const img = document.createElement('img');
            img.classList.add('w-[3.56rem]', 'h-[2.56rem]');
            img.src = '../images/save-icon.png';
            imgDiv.appendChild(img);
            innerDiv.appendChild(imgDiv);
            
            const textDiv = document.createElement('div');
            textDiv.classList.add('w-full', 'flex');
            
            const p = document.createElement('p');
            p.textContent = salesChannel.name;
            textDiv.appendChild(p);
            innerDiv.appendChild(textDiv);
            
            const checkboxDiv = document.createElement('div');
            checkboxDiv.classList.add('flex', 'items-center', 'justify-end', 'mr-[1.5rem]');
            
            label.appendChild(input);
            label.appendChild(span);
            checkboxDiv.appendChild(label);
            
            innerDiv.appendChild(checkboxDiv);
            
            divContainer.appendChild(innerDiv);
            salesChannelList.appendChild(divContainer);
        });
    }
    
    renderSalesChannels();
    
    function searchSalesChannels(salesChannelText) {
        salesChannelsData.forEach((salesChannel) => {
            const li = document.getElementById(`sales_div_${salesChannel.id}`);
            if (
                !salesChannelText ||
                salesChannel.name.toLowerCase().includes(salesChannelText.toLowerCase())
            ) {
                li.classList.remove("hidden");
            } else {
                li.classList.add("hidden");
            }
        });
    }
    
    const salesChannelSearchInput = document.getElementById("salesChannelSearchInput");
    
    if (salesChannelSearchInput) {
        salesChannelSearchInput.addEventListener("input", () => {
            const salesChannelText = salesChannelSearchInput.value.trim();
            searchSalesChannels(salesChannelText);
        });
    }
});
