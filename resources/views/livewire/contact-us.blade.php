<div>
    <section class="section-gap">
        <div class="container">
            <div class="row">

                <div class="col-12 col-lg-6">
                    <h2>متابعة الحجوزات</h2>
                    <div class="clear20x"></div>

                    @if(\HackerESQ\Settings\Facades\Settings::get('instagram'))
                    <p><i class="fa fa-instagram fa-2x"></i>&nbsp;  <a href="{{ \HackerESQ\Settings\Facades\Settings::get('instagram') }}" target="_blank">{{ str_replace('https://instagram.com/' , '@' , \HackerESQ\Settings\Facades\Settings::get('instagram') ) }}</a></p>
                    @endif
                    @if(\HackerESQ\Settings\Facades\Settings::get('whatsapp'))
                    <p><i class="fa fa-whatsapp fa-2x"></i>&nbsp;   <a href="https://wa.me/{{ str_replace([' ' , '-' , '_'] ,'' , \HackerESQ\Settings\Facades\Settings::get('whatsapp')) }}" dir="ltr">{{ \HackerESQ\Settings\Facades\Settings::get('whatsapp') }}</a></p>
                    @endif
                    @if(\HackerESQ\Settings\Facades\Settings::get('email'))
                    <p><i class="fa fa-envelope fa-2x"></i>&nbsp;   <a href="mailto:{{ \HackerESQ\Settings\Facades\Settings::get('email') }}" target="_blank">{{ \HackerESQ\Settings\Facades\Settings::get('email') }}</a></p>
                    @endif
                    <div class="clear20x"></div>


                    <h5>أي أسئلة يمكنك أن تصل إلينا من هنا!:</h5>
                    <div class="clear30x"></div>

                    <form onsubmit="return false;" class="contact">

                        @if(! $messageAlert)
                        <input type="text" wire:model.lazy="form.name" class="@error('form.name') is-invalid @enderror" placeholder="الأسم" onblur="this.placeholder='الأسم'" onclick="this.placeholder=''">


                        <input type="text" wire:model.lazy="form.email" class="@error('form.email') is-invalid @enderror" placeholder="الايميل" onblur="this.placeholder='الايميل'" onclick="this.placeholder=''">

                        <div class="clear20x"></div>

                        <input type="text" wire:model.lazy="form.phone" class="@error('form.phone') is-invalid @enderror" placeholder="رقم الهاتف" onblur="this.placeholder='رقم الهاتف'" onclick="this.placeholder=''">

                        <input type="text" wire:model.lazy="form.subject" class="@error('form.subject') is-invalid @enderror" placeholder="الموضوع" onblur="this.placeholder='الموضوع'" onclick="this.placeholder=''">
                        <div class="clear20x"></div>

                        <textarea type="text" wire:model.lazy="form.message" class="@error('form.message') is-invalid @enderror" rows="3" placeholder="رسالتك هنا ..." onblur="this.placeholder='رسالتك هنا ...'" onclick="this.placeholder=''"></textarea>
                        <div class="clear30x"></div>

                        <button type="button"  wire:loading.attr="disabled"
                                 wire:click.prevent="save" class="btn-md">ارسال</button>

                        @else
                        <div class="sucess2">لخدمتك بشكل أفضل ، يرجى ملاحظة أن الرسالة مخصصة للاستفسارات فقط وليس للحجز. شكرًا لك.</div>
                        @endif
                    </form>
                </div>



            </div>
        </div>
    </section>
</div>
