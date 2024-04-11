<x-layout._header-dependencies :sidenavActive="$sidenavActive" />

<body class="flex bg-[#F3F4F8] text-[#717171] text-[14px]" style="font-family: 'Inter', sans-serif;">
<x-header.header/>
    <div class="flex h-full pt-20 w-full gap-[1.9rem]">
        <div class="h-full">
            <x-sidenav.sidenav :sidenavActive="$sidenavActive"/>
        </div>
        <div class="pt-20 hd:w-[98rem] uhd:w-[138rem]">
            <div>
                <div>
                    <p class="text-[#717171] text-[20px] font-bold">
                        Product aanmaken
                    </p>
                <div>
                <x-product.create.header />
              
                <form method="POST" action="/products" enctype="multipart/form-data">
                    @csrf

                    <div id="stepOne" class="step">
                        <x-product.create.stepOne.index />
                    </div>

                    <div id="stepTwo" class="step" style="display: none;">
                        <x-product.create.stepTwo.photos />
                    </div>

                    <div id="stepThree" class="step" style="display: none;">
                        <x-product.create.stepThree.categories :categories="$categories" />
                    </div>

                    <div id="stepFour" class="step" style="display: none;">
                        <x-product.create.stepFour.properties :properties="$properties" />
                    </div>

                    <div id="stepFive" class="step" style="display: none;">
                        <x-product.create.stepFive.stock :locations="$locations" />
                    </div>

                    <div id="stepSix" class="step" style="display: none;">
                        <x-product.create.stepSix.sales-channels :salesChannels="$salesChannels" />
                    </div>

                    <x-product.create.create-error-message :errors="$errors"/>

                    <div class="flex w-full items-center">
                        <div id="prevBtn" class="mr-[2rem]">
                            <x-product.buttons.create-previous-button />
                        </div>

                        <div>
                            <x-product.buttons.create-cancel-button />
                        </div>

                        <div class="w-full flex justify-end">
                            <x-product.buttons.create-next-button />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

<x-layout._footer-dependencies />
<script type="text/javascript" src="{{ asset('./assets/js/product/navbar.js') }}"></script>
<script>
    const steps = document.querySelectorAll('.step');
    let currentStep = 0;

    function showStep(stepIndex) {
        steps.forEach((step, index) => {
            if (index === stepIndex) {
                step.style.display = 'block';
            } else {
                step.style.display = 'none';
            }
        });

        if (stepIndex === 0) {
            document.getElementById('prevBtn').style.display = 'none';
        } else {
            document.getElementById('prevBtn').style.display = 'inline-block';
        }

        if (stepIndex === steps.length - 1) {
            document.getElementById('nextBtn').style.display = 'none';
        } else {
            document.getElementById('nextBtn').style.display = 'inline-block';
        }

        // Update progress bar
        const progressBar = document.getElementById('progress');
        const progressPercentage = (stepIndex / (steps.length - 1)) * 100;
        progressBar.style.width = progressPercentage + '%';

        // Display current step
        const currentStepText = document.getElementById('currentStep');
        currentStepText.textContent = `Step ${stepIndex + 1} of ${steps.length}`;
    }

    document.getElementById('nextBtn').addEventListener('click', () => {
        if (currentStep < steps.length - 1) {
            currentStep++;
            showStep(currentStep);
        }
    });

    document.getElementById('prevBtn').addEventListener('click', () => {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    });

    showStep(currentStep);
</script>
</html>
