<?php

namespace Workdo\PropertyManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmailTemplate;
use App\Models\EmailTemplateLang;

class EmailTemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $emailTemplate = [
            'New Property Invoice',
            'Property Invoice Payment Create',
        ];

        $defaultTemplate = [
            'New Property Invoice' => [
                'subject' => 'Property Invoice Create',
                'variables' => '{
                    "Property Invoice Number": "invoice_id",
                    "Property Invoice Tenant": "invoice_tenant",
                    "Property Invoice Status": "invoice_status",
                    "Property Invoice Total": "invoice_sub_total",
                    "Property Invoice Issue Date": "created_at",
                    "App Url": "app_url",
                    "Company Name": "company_name",
                    "App Name": "app_name"
                  }',
                'lang' => [
                    'ar' => 'العزيز<span style="font-size: 12pt;">&nbsp;{invoice_tenant}</span><span style="font-size: 12pt;">,</span><br><br>لقد قمنا بإعداد الفاتورة التالية من أجلك<span style="font-size: 12pt;">: </span><strong style="font-size: 12pt;">&nbsp;{invoice_id}</strong><br><br>حالة الفاتورة<span style="font-size: 12pt;">: {invoice_status}</span><br><br><br>يرجى الاتصال بنا للحصول على مزيد من المعلومات<span style="font-size: 12pt;">.</span><br><br>أطيب التحيات<span style="font-size: 12pt;">,</span><br>{app_name}',
                    'da' => 'Kære<span style="font-size: 12pt;">&nbsp;{invoice_tenant}</span><span style="font-size: 12pt;">,</span><br><br>Vi har udarbejdet følgende faktura til dig<span style="font-size: 12pt;">:&nbsp;&nbsp;{invoice_id}</span><br><br>Fakturastatus: {invoice_status}<br><br>Kontakt os for mere information<span style="font-size: 12pt;">.</span><br><br>Med venlig hilsen<span style="font-size: 12pt;">,</span><br>{app_name}',
                    'de' => '<p><b>sehr geehrter</b><span style="font-size: 12pt;">&nbsp;{invoice_tenant}</span><br><br>Wir haben die folgende Rechnung für Sie vorbereitet<span style="font-size: 12pt;">: {invoice_id}</span><br><br><b>Rechnungsstatus</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Bitte kontaktieren Sie uns für weitere Informationen<span style="font-size: 12pt;">.</span><br><br><b>Mit freundlichen Grüßen</b><span style="font-size: 12pt;">,</span><br>{app_name}</p>',
                    'en' => '<p><span style="font-size: 12pt;"><strong>Dear</strong>&nbsp;{invoice_tenant}</span><span style="font-size: 12pt;">,</span></p>
                            <p><span style="font-size: 12pt;">We have prepared the following invoice for you :#{invoice_id}</span></p>
                            <p><span style="font-size: 12pt;"><strong>Invoice Status</strong> : {invoice_status}</span></p>
                            <p>Please Contact us for more information.</p>
                            <p><span style="font-size: 12pt;">&nbsp;</span></p>
                            <p><strong>Kind Regards</strong>,<br /><span style="font-size: 12pt;">{app_name}</span></p>',
                    'es' => '<p><b>Querida</b><span style="font-size: 12pt;">&nbsp;{invoice_tenant}</span><span style="font-size: 12pt;">,</span></p><p>Hemos preparado la siguiente factura para ti<span style="font-size: 12pt;">:&nbsp;&nbsp;{invoice_id}</span></p><p><b>Estado de la factura</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Por favor contáctenos para más información<span style="font-size: 12pt;">.</span></p><p><b>Saludos cordiales</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                    'fr' => '<p><b>Cher</b><span style="font-size: 12pt;">&nbsp;{invoice_tenant}</span><span style="font-size: 12pt;">,</span></p><p>Nous avons préparé la facture suivante pour vous<span style="font-size: 12pt;">: {invoice_id}</span></p><p><b>État de la facture</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Veuillez nous contacter pour plus d\'informations<span style="font-size: 12pt;">.</span></p><p><b>Sincères amitiés</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                    'it' => '<p><b>Caro</b><span style="font-size: 12pt;">&nbsp;{invoice_tenant}</span><span style="font-size: 12pt;">,</span></p><p>Abbiamo preparato per te la seguente fattura<span style="font-size: 12pt;">:&nbsp;&nbsp;{invoice_id}</span></p><p><b>Stato della fattura</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Vi preghiamo di contattarci per ulteriori informazioni<span style="font-size: 12pt;">.</span></p><p><b>Cordiali saluti</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                    'ja' => '親愛な<span style="font-size: 12pt;">&nbsp;{invoice_tenant}</span><span style="font-size: 12pt;">,</span><br><br>以下の請求書をご用意しております。<span style="font-size: 12pt;">: {invoice_tenant}</span><br><br>請求書のステータス<span style="font-size: 12pt;">: {invoice_status}</span><br><br>詳しくはお問い合わせください<span style="font-size: 12pt;">.</span><br><br>敬具<span style="font-size: 12pt;">,</span><br>{app_name}',
                    'nl' => '<p><b>Lieve</b><span style="font-size: 12pt;">&nbsp;{invoice_tenant}</span><span style="font-size: 12pt;">,</span></p><p>We hebben de volgende factuur voor u opgesteld<span style="font-size: 12pt;">: {invoice_id}</span></p><p><b>Factuurstatus</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Voor meer informatie kunt u contact met ons opnemen<span style="font-size: 12pt;">.</span></p><p><b>Vriendelijke groeten</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                    'pl' => '<p><b>Drogi</b><span style="font-size: 12pt;">&nbsp;{invoice_tenant}</span><span style="font-size: 12pt;">,</span></p><p>Przygotowaliśmy dla Ciebie następującą fakturę<span style="font-size: 12pt;">: {invoice_id}</span></p><p><b>Status faktury</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Skontaktuj się z nami, aby uzyskać więcej informacji<span style="font-size: 12pt;">.</span></p><p><b>Z poważaniem</b><span style="font-size: 12pt;"><b>,</b><br></span>{app_name}</p>',
                    'ru' => '<p><b>дорогая</b><span style="font-size: 12pt;">&nbsp;{invoice_tenant}</span><span style="font-size: 12pt;">,</span></p><p>Мы подготовили для вас следующий счет<span style="font-size: 12pt;">: {invoice_id}</span></p><p><b>Статус счета</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Пожалуйста, свяжитесь с нами для получения дополнительной информации<span style="font-size: 12pt;">.</span></p><p><b>С уважением</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                    'pt' => '<p><b>Querida</b><span style="font-size: 12pt;">&nbsp;{invoice_tenant}</span><span style="font-size: 12pt;">,</span></p><p>Preparamos a seguinte fatura para você<span style="font-size: 12pt;">: {invoice_id}</span></p><p><b>Status da fatura</b><span style="font-size: 12pt;">: {invoice_status}</span></p><p>Entre em contato conosco para mais informações.<span style="font-size: 12pt;">.</span></p><p><b>Atenciosamente</b><span style="font-size: 12pt;">,<br></span>{app_name}</p>',
                ],
            ],
            'Property Invoice Payment Create' => [
                'subject' => 'Property Invoice Payment Create',
                'variables' => '{
                    "App Name": "app_name",
                    "Company Name": "company_name",
                    "App Url": "app_url",
                    "Payment Name": "payment_name",
                    "Invoice Number": "invoice_number",
                    "Payment Amount": "payment_amount",
                    "Payment Date": "payment_date"
                  }',
                'lang' => [
                    'ar' => '<p>مرحبا</p>
                    <p>مرحبا بك في { app_name }</p>
                    <p>عزيزي { payment_name }</p>
                    <p>يسعدنا إبلاغك بأننا تلقينا دفعتك الخاصة بفاتورة العقار {invoice_number} المقدمة في {Payment_date}. تفاصيل الدفع الخاصة بك هي كما يلي:</p>
                    <p>مبلغ الدفع : {Payment_amount}</p>
                    <p>إن دفعك الفوري موضع تقدير كبير، ونحن نقدر التزامك كمستأجر في ممتلكاتنا. رضاكم يهمنا، ونحن هنا لنقدم لكم أفضل الخدمات.</p>
                    <p>إذا كانت لديك أي أسئلة أو استفسارات بخصوص دفعتك أو خدماتنا العقارية، فلا تتردد في الاتصال بنا. شكرًا لك على اختيار {app_name}.</p>
                    <p>&nbsp;</p>
                    <p>Regards,</p>
                    <p>{ company_name }</p>
                    <p>{ app_url }</p>',
                    'da' => '<p>Hej.</p>
                    <p>Velkommen til { app_name }</p>
                    <p>K&aelig;re { payment_name }</p>
                    <p>Vi er glade for at kunne meddele dig, at vi har modtaget din betaling for ejendomsfakturaen {invoice_number} indsendt den {payment_date}. Dine betalingsoplysninger er som følger:</p>
                    <p>Betalingsbeløb : {payment_amount}</p>
                    <p>Din hurtige betaling er meget værdsat, og vi værdsætter dit engagement som lejer i vores ejendom. Din tilfredshed er vigtig for os, og vi er her for at give dig den bedste service.</p>
                    <p>Hvis du har spørgsmål eller bekymringer vedrørende din betaling eller vores ejendomstjenester, er du velkommen til at kontakte os. Tak, fordi du valgte {app_name}.</p>
                    <p>&nbsp;</p>
                    <p>Med venlig hilsen</p>
                    <p>{ company_name }</p>
                    <p>{ app_url }</p>',
                    'de' => '<p>Hi,</p>
                    <p>Willkommen bei {app_name}</p>
                    <p>Sehr geehrter {payment_name}</p>
                    <p>Wir freuen uns, Ihnen mitteilen zu können, dass wir Ihre Zahlung für die am {payment_date} eingereichte Immobilienrechnung {invoice_number} erhalten haben. Ihre Zahlungsdaten lauten wie folgt:</p>
                    <p>Zahlungsbetrag: {payment_amount}</p>
                    <p>Ihre pünktliche Zahlung wissen wir sehr zu schätzen und wir schätzen Ihr Engagement als Mieter unserer Immobilie. Ihre Zufriedenheit ist uns wichtig und wir sind hier, um Ihnen den besten Service zu bieten.</p>
                    <p>Wenn Sie Fragen oder Bedenken bezüglich Ihrer Zahlung oder unseren Immobiliendienstleistungen haben, können Sie sich gerne an uns wenden. Vielen Dank, dass Sie sich für {app_name} entschieden haben.</p>
                    <p>&nbsp;</p>
                    <p>Betrachtet,</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>',
                    'en' => '<p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Hi,</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Welcome to {app_name}</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Dear {payment_name}</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">We are pleased to inform you that we have received your payment for Property Invoice {invoice_number} submitted on {payment_date}. Your payment details are as follows:</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Payment Amount : {payment_amount}</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Your prompt payment is greatly appreciated, and we value your commitment as a tenant in our property. Your satisfaction is important to us, and we are here to provide you with the best services.</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">If you have any questions or concerns regarding your payment or our property services, feel free to contact us. Thank you for choosing {app_name}.</span></span></p>
                    <p>&nbsp;</p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">Regards,</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">{company_name}</span></span></p>
                    <p><span style="color: #1d1c1d; font-family: Slack-Lato, Slack-Fractions, appleLogo, sans-serif;"><span style="font-size: 15px; font-variant-ligatures: common-ligatures;">{app_url}</span></span></p>',
                    'es' => '<p>Hola,</p>
                    <p>Bienvenido a {app_name}</p>
                    <p>Estimado {payment_name}</p>
                    <p>Nos complace informarle que hemos recibido el pago de la factura de propiedad {invoice_number} enviada el {payment_date}. Sus datos de pago son los siguientes:</p>
                    <p>Importe del pago: {payment_amount}</p>
                    <p>Apreciamos mucho su pronto pago y valoramos su compromiso como inquilino de nuestra propiedad. Su satisfacción es importante para nosotros y estamos aquí para brindarle los mejores servicios.</p>
                    <p>Si tiene alguna pregunta o inquietud con respecto a su pago o nuestros servicios inmobiliarios, no dude en contactarnos. Gracias por elegir {app_name}.</p>
                    <p>&nbsp;</p>
                    <p>Considerando,</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>',
                    'fr' => '<p>Salut,</p>
                    <p>Bienvenue dans { app_name }</p>
                    <p>Cher { payment_name }</p>
                    <p>Nous avons le plaisir de vous informer que nous avons reçu votre paiement pour la facture de propriété {invoice_number} soumise le {payment_date}. Vos détails de paiement sont les suivants :</p>
                    <p>Montant du paiement : {payment_amount}</p>
                    <p>Votre paiement rapide est grandement apprécié et nous apprécions votre engagement en tant que locataire de notre propriété. Votre satisfaction est importante pour nous et nous sommes là pour vous fournir les meilleurs services.</p>
                    <p>Si vous avez des questions ou des préoccupations concernant votre paiement ou nos services immobiliers, nhésitez pas à nous contacter. Merci davoir choisi {app_name}.</p>
                    <p>&nbsp;</p>
                    <p>Regards,</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>',
                    'it' => '<p>Ciao,</p>
                    <p>Benvenuti in {app_name}</p>
                    <p>Caro {payment_name}</p>
                    <p>Siamo lieti di informarti che abbiamo ricevuto il pagamento per la fattura della proprietà {invoice_number} inviata il {payment_date}. I dettagli del pagamento sono i seguenti:</p>
                    <p>Importo del pagamento: {payment_amount}</p>
                    <p>Il tuo tempestivo pagamento è molto apprezzato e apprezziamo il tuo impegno come inquilino nella nostra proprietà. La tua soddisfazione è importante per noi e siamo qui per fornirti i migliori servizi.</p>
                    <p>Se hai domande o dubbi riguardanti il ​​pagamento o i nostri servizi immobiliari, non esitare a contattarci. Grazie per aver scelto {app_name}.</p>
                    <p>&nbsp;</p>
                    <p>Riguardo,</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>',
                    'ja' => '<p>こんにちは。</p>
                    <p>{app_name} へようこそ</p>
                    <p>{ payment_name} に出れます</p>
                    <p>{payment_date} に提出された不動産請求書 {invoice_number} のお支払いを受領したことをお知らせいたします。お支払いの詳細は次のとおりです:</p>
                    <p>支払い金額 : {payment_amount}</p>
                    <p>お客様の迅速な支払いに大変感謝しており、私たちは私たちの施設のテナントとしてのあなたのコミットメントを大切にしています。当社にとってお客様の満足は重要であり、最高のサービスを提供するためにここにいます。</p>
                    <p>お支払いや不動産サービスに関してご質問やご不明な点がございましたら、お気軽にお問い合わせください。 {app_name} をお選びいただきありがとうございます。</p>
                    <p>&nbsp;</p>
                    <p>よろしく</p>
                    <p>{ company_name}</p>
                    <p>{app_url}</p>',
                    'nl' => '<p>Hallo,</p>
                    <p>Welkom bij { app_name }</p>
                    <p>Beste { payment_name }</p>
                    <p>We laten u graag weten dat we uw betaling voor de vastgoedfactuur {invoice_number}, ingediend op {payment_date}, hebben ontvangen. Uw betalingsgegevens zijn als volgt:</p>
                    <p>Betalingsbedrag: {payment_amount}</p>
                    <p>Uw tijdige betaling wordt zeer op prijs gesteld en wij waarderen uw inzet als huurder in ons pand. Uw tevredenheid is belangrijk voor ons en wij zijn er om u de beste service te bieden.</p>
                    <p>Als u vragen of opmerkingen heeft over uw betaling of onze vastgoeddiensten, neem dan gerust contact met ons op. Bedankt dat u voor {app_name} heeft gekozen.</p>
                    <p>&nbsp;</p>
                    <p>Betreft:</p>
                    <p>{ company_name }</p>
                    <p>{ app_url }</p>',
                    'pl' => '<p>Witam,</p>
                    <p>Witamy w aplikacji {app_name }</p>
                    <p>Droga {payment_name }</p>
                    <p>Mamy przyjemność poinformować Państwa, że ​​otrzymaliśmy Państwa płatność za fakturę za nieruchomość {invoice_number} przedstawioną dnia {payment_date}. Twoje dane do płatności są następujące:</p>
                    <p>Kwota płatności: {payment_amount}</p>
                    <p>Bardzo doceniamy Twoją szybką płatność i cenimy Twoje zaangażowanie jako najemcy naszej nieruchomości. Twoja satysfakcja jest dla nas ważna i jesteśmy tutaj, aby zapewnić Ci najlepsze usługi.</p>
                    <p>Jeśli masz jakiekolwiek pytania lub wątpliwości dotyczące płatności lub naszych usług związanych z nieruchomościami, skontaktuj się z nami. Dziękujemy za wybranie aplikacji {app_name}.</p>
                    <p>&nbsp;</p>
                    <p>W odniesieniu do</p>
                    <p>{company_name }</p>
                    <p>{app_url }</p>',
                    'ru' => '<p>Привет.</p>
                    <p>Вас приветствует { app_name }</p>
                    <p>Дорогая { payment_name }</p>
                    <p>Мы рады сообщить вам, что мы получили ваш платеж по счету за недвижимость {invoice_number}, отправленному {payment_date}. Ваши платежные реквизиты следующие:</p>
                    <p>Сумма платежа: {payment_amount}</p>
                    <p>Мы очень ценим вашу своевременную оплату и ценим вашу приверженность как арендатора нашей недвижимости. Для нас важно ваше удовлетворение, и мы здесь, чтобы предоставить вам лучшие услуги.</p>
                    <p>Если у вас есть какие-либо вопросы или сомнения относительно оплаты или наших услуг в сфере недвижимости, свяжитесь с нами. Благодарим вас за выбор {app_name}.</p>
                    <p>&nbsp;</p>
                    <p>С уважением,</p>
                    <p>{ company_name }</p>
                    <p>{ app_url }</p>',
                    'pt' => '<p>Oi,</p>
                    <p>Bem-vindo a {app_name}</p>
                    <p>Querido {payment_name}</p>
                    <p>Temos o prazer de informar que recebemos o pagamento da fatura de propriedade {invoice_number} enviada em {payment_date}. Seus detalhes de pagamento são os seguintes:</p>
                    <p>Valor do pagamento: {payment_amount}</p>
                    <p>Seu pagamento imediato é muito apreciado e valorizamos seu compromisso como inquilino em nossa propriedade. Sua satisfação é importante para nós e estamos aqui para lhe oferecer os melhores serviços.</p>
                    <p>Se você tiver alguma dúvida ou preocupação em relação ao seu pagamento ou aos nossos serviços imobiliários, não hesite em nos contatar. Obrigado por escolher o {app_name}.</p>
                    <p>&nbsp;</p>
                    <p>Considera,</p>
                    <p>{company_name}</p>
                    <p>{app_url}</p>',
                ],
            ],


        ];

        foreach($emailTemplate as $eTemp)
        {
            $table = EmailTemplate::where('name',$eTemp)->where('module_name','PropertyManagement')->exists();
            if(!$table)
            {
                $emailtemplate=  EmailTemplate::create(
                    [
                        'name' => $eTemp,
                        'from' => 'PropertyManagement',
                        'module_name' => 'PropertyManagement',
                        'created_by' => 1,
                        'workspace_id' => 0
                    ]
                );
                foreach($defaultTemplate[$eTemp]['lang'] as $lang => $content)
                {
                    EmailTemplateLang::create(
                        [
                            'parent_id' => $emailtemplate->id,
                            'lang' => $lang,
                            'subject' => $defaultTemplate[$eTemp]['subject'],
                            'variables' => $defaultTemplate[$eTemp]['variables'],
                            'content' => $content,
                        ]
                    );
                }
            }
        }

    }
}
