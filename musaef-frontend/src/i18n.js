import { createI18n } from 'vue-i18n';

const messages = {
  ar: {
    navbar: {
      announcement: 'تم إنقاذ 3,580 مريضاً بفضل الله ثم المتبرعين بالدم.',
      home: 'الرئيسية',
      about: 'من نحن',
      features: 'مميزات المنصة',
      reviews: 'التقييمات والآراء',
      partners: 'بالتعاون مع',
      bloodGuide: 'دليل التبرع بالدم',
      medicalTips: 'نصائح طبية',
      faq: 'الأسئلة الشائعة',
      login: 'تسجيل الدخول',
      register: 'إنشاء حساب',
      donateNow: 'تبرع الآن'
    },
    hero: {
      smallTitle: 'مُسْعِف...',
      title: 'المنصة الذكية للتبرع بالدم في الحالات الطارئة',
      titleLine1: 'كل قطرة دم',
      titleLine2: 'تفرق في حياة إنسان',
      titleHtml: 'المنصة الذكية للتبرع<br>بالدم وقت الطوارئ',
      desc: 'نربط المتبرعين والمستشفيات بسرعة وذكاء لنضمن وصول الدم إلى من تحتاجه في الوقت المناسب.',
      description: 'نربط المتبرعين بالدم مع المستشفيات والمرضى بسرعة وذكاء لضمان وصول الدم في الوقت المناسب.',
      explore: 'استكشف المنصة',
      donate: 'تبرع الآن',
      donateNow: 'تبرع الآن',
      learnMore: 'تعرف علينا'
    },
    home: {
      howItWorksTitle: 'كيف تعمل المنصة؟!',
      step1Title: 'التسجيل في المنصة',
      step1Desc: 'إنشاء حساب المتبرع وتحديد موقعك.',
      step2Title: 'إكمال الملف الصحي',
      step2Desc: 'إدخال بياناتك الصحية بأمان وسرية.',
      step3Title: 'استقبال طلبات التبرع',
      step3Desc: 'استقبال الطلبات القريبة حسب فصيلة دمك.',
      step4Title: 'المطابقة الذكية',
      step4Desc: 'نظام ذكي يطابق بين المتبرع والمحتاج.',
      step5Title: 'التوجه لمركز التبرع',
      step5Desc: 'التوجه للمركز المحدد للتبرع وإنقاذ حياة.',
      statSupported: 'عدد الحالات التي تم دعمها',
      statRequests: 'عدد طلبات التبرع',
      statHospitals: 'عدد المستشفيات',
      statDonors: 'عدد المتبرعين المسجلين',
      urgentTitle: 'أحدث الحالات الطارئة',
      viewAll: 'عرض جميع الحالات',
      veryCritical: 'حرج جداً',
      urgent: 'عاجل',
      noCases: 'لا توجد حالات طارئة حالياً.',
      requiredUnits: 'المطلوب: {count} وحدات دم',
      donate: 'تبرع الآن',
      share: 'مشاركة الحالة',
      shareSuccess: 'تم نسخ رابط الصفحة لمشاركته!'
    },
    about: {
      title: 'من نحن',
      desc: 'منصة ذكية تهدف إلى تسهيل التبرع بالدم، إنقاذ الأرواح باستخدام الذكاء الاصطناعي والتقنيات الحديثة. نربط بين المتبرعين والمحتاجين في لحظات الطوارئ لضمان وصول الدم في الوقت المناسب.',
      descriptionHtml: 'منصة ذكية تهدف إلى تسهيل التبرع بالدم، إنقاذ الأرواح باستخدام الذكاء الاصطناعي والتقنيات الحديثة. نربط بين المتبرعين والمحتاجين في لحظات الطوارئ لضمان وصول الدم في الوقت المناسب.',
      visionTitle: 'رؤيتنا',
      visionDesc: 'أن نكون المنصة الرائدة والأكثر موثوقية في مجال التبرع بالدم على مستوى الوطن العربي.',
      missionTitle: 'رسالتنا',
      missionDesc: 'توفير الدم بأسرع وقت وبأعلى معايير الأمان من خلال منصة موثوقة تعتمد على التقنية والتعاون الإنساني لإنقاذ الأرواح.',
      goalsTitle: 'أهدافنا',
      goalsDesc: 'إنقاذ الأرواح عبر تسهيل التبرع بدم آمن وسريع وفعال باستخدام التقنية والذكاء الاصطناعي.',
      featuresTitle: 'مميزات المنصة؟!',
      reviewsTitle: 'التقييمات والآراء',
      partnersTitle: 'بالتعاون مع',
      defaultLocation: 'غزة - فلسطين',
      loadingPartners: 'جاري تحميل الشركاء...',
      features: {
        predict: { title: 'التنبؤ الذكي', desc: 'خوارزميات تتنبأ بالاحتياجات المستقبلية للدم.' },
        inventory: { title: 'إدارة المخزون', desc: 'متابعة وتحديث المخزون بشكل لحظي ومستمر.' },
        rewards: { title: 'نظام المكافآت', desc: 'نقاط وجوائز تشجيعية للمتبرعين الدائمين.' },
        alerts: { title: 'التنبيهات العاجلة', desc: 'إشعارات فورية للحالات الطارئة والقريبة منك.' },
        maps: { title: 'الخرائط التفاعلية', desc: 'تحديد أماكن المستشفيات ومراكز التبرع بدقة.' },
        matching: { title: 'المطابقة الفورية', desc: 'ربط المتبرع المناسب بالمريض في الوقت القياسي.' }
      },
      reviews: {
        r1: { name: 'أحمد محمود', role: 'متبرع دائم', text: 'المنصة سهلت علي التبرع وربطتني بحالات عاجلة في وقت قياسي. تجربة ممتازة وإنسانية.' },
        r2: { name: 'مريم سعيد', role: 'مستفيدة', text: 'بفضل الله ثم المنصة حصلنا على وحدات الدم المطلوبة لوالدي في أسرع وقت ممكن.' },
        r3: { name: 'مجمع الشفاء الطبي', role: 'شريك طبي', text: 'نظام دقيق وفعال يساهم بشكل مباشر في دعم بنك الدم وتغطية الاحتياجات الطارئة.' }
      },
      hospitals: {
        h1: { name: 'مجمع الشفاء الطبي', address: 'غزة - الرمال' },
        h2: { name: 'جمعية بنك الدم المركزي', address: 'غزة - شارع الوحدة' },
        h3: { name: 'بنك الدم المركزي - وزارة الصحة', address: 'غزة - النصر' },
        h4: { name: 'مستشفى الأهلي العربي (المعمداني)', address: 'غزة - الزيتون' },
        h5: { name: 'مستشفى القدس - الهلال الأحمر', address: 'غزة - تل الهوى' },
        h6: { name: 'مستشفى كمال عدوان', address: 'شمال غزة - بيت لاهيا' },
        h7: { name: 'المستشفى الإندونيسي', address: 'شمال غزة - بيت لاهيا' },
        h8: { name: 'مجمع ناصر الطبي', address: 'خانيونس - وسط المدينة' },
        h9: { name: 'مستشفى أبو يوسف النجار', address: 'رفح - الجنينة' }
      }
    },
    guide: {
      mainTitle: 'دليل التبرع',
      subTitle: 'والإرشادات الطبية',
      heroTitle: 'دليل التبرع',
      heroSubtitle: 'والإرشادات الطبية',
      heroDesc: 'دليل مختصر لفهم توافق فصائل الدم وشروط التبرع الآمن والإرشادات الطبية الأساسية لضمان تبرع آمن وإنقاذ الأرواح.',
      desc: 'دليل مختصر لفهم توافق فصائل الدم وشروط التبرع الآمن والإرشادات الطبية الأساسية لضمان تبرع آمن وإنقاذ الأرواح.',
      compTableTitle: 'جدول توافق فصائل الدم',
      compatibilityTitle: 'جدول توافق فصائل الدم',
      donorToWhom: 'التوافق في التبرع (من يستطيع التبرع لمن؟)',
      donorToWhomDesc: 'يوضح من يمكنه التبرع لكل فصيلة دم بناءً على توافق فصائل الدم.',
      donateCompatTitle: 'التوافق في التبرع (من يستطيع التبرع لمن؟)',
      donateCompatDesc: 'يوضح من يمكنه التبرع لكل فصيلة دم بناءً على توافق فصائل الدم.',
      receiverFromWhom: 'التوافق في الاستقبال (من يستطيع استقبال الدم؟)',
      receiverFromWhomDesc: 'يوضح من يمكنه استقبال الدم من كل فصيلة دم بأمان.',
      receiveCompatTitle: 'التوافق في الاستقبال (من يستطيع استقبال الدم؟)',
      receiveCompatDesc: 'يوضح من يمكنه استقبال الدم من كل فصيلة دم بأمان.',
      compatible: 'متوافق',
      compatibleSub: 'يمكن التبرع',
      incompatible: 'غير متوافق',
      incompatibleSub: 'لا يمكن التبرع',
      aiSearchTitle: 'البحث الذكي عن مراكز التبرع',
      aiSearchDesc: 'اختر فصيلة الدم للبحث عن أقرب المراكز والمستشفيات المتاحة حالياً.',
      searchBtn: 'بحث',
      searching: 'جاري البحث عن أقرب المراكز...',
      etaUnit: 'دقيقة',
      available: 'المتاح',
      unitsUnit: 'وحدات',
      kmUnit: 'كم',
      aiPromptPlaceholder: 'حدد فصيلة الدم واضغط بحث لعرض أقرب مراكز التبرع المتاحة.',
      tipsTitle: 'قسم النصائح والإرشادات',
      tipsSectionTitle: 'قسم النصائح والإرشادات',
      ageTitle: 'العمر المناسب',
      ageDesc: 'يجب أن يكون عمرك بين 18 و 65 عاماً للتبرع بالدم.',
      weightTitle: 'الوزن المناسب',
      weightDesc: 'يجب أن يكون وزنك 50 كجم على الأقل للتبرع بالدم.',
      intervalTitle: 'متى يمكن التبرع مرة أخرى؟',
      intervalDesc: 'يمكنك التبرع كل 8 أسابيع (56 يوماً) للرجال، وكل 12 أسبوعاً (84 يوماً) للنساء.',
      tips: {
        age: { title: 'العمر المناسب', desc: 'يجب أن يكون عمرك بين 18 و 65 عاماً للتبرع بالدم.' },
        weight: { title: 'الوزن المناسب', desc: 'يجب أن يكون وزنك 50 كجم على الأقل للتبرع بالدم.' },
        frequency: { title: 'متى يمكن التبرع مرة أخرى؟', desc: 'يمكنك التبرع كل 8 أسابيع (56 يوماً) للرجال، وكل 12 أسبوعاً (84 يوماً) للنساء.' }
      },
      faqTitle: 'الأسئلة الشائعة',
      faqSearchPlaceholder: 'ابحث عن سؤال...',
      contactSupport: 'إذا لم تجد الإجابة، تواصل معنا وسيقوم فريقنا بالرد عليك.',
      contactHeading: 'تواصل مع الدعم الفني',
      contactSubheading: 'إذا لم تجد الإجابة، تواصل معنا وسيقوم فريقنا بالرد عليك.',
      contactForm: {
        name: 'الاسم الكامل',
        email: 'البريد الإلكتروني',
        subject: 'عنوان الرسالة',
        message: 'اكتب نص رسالتك هنا...',
        send: 'إرسال الرسالة',
        sending: 'جاري الإرسال...',
        successMsg: 'تم إرسال رسالتك بنجاح! سنرد عليك في أقرب وقت.',
        errorMsg: 'حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة لاحقاً.'
      },
      faqs: {
        q1: { q: 'هل التبرع بالدم آمن؟', a: 'نعم، عملية التبرع بالدم آمنة تماماً حيث يتم استخدام أدوات معقمة وتستعمل لمرة واحدة فقط لكل متبرع.' },
        q2: { q: 'كم يستغرق وقت التبرع بالدم؟', a: 'تستغرق عملية التبرع الفعلية ما بين 8 إلى 10 دقائق فقط، بينما تستغرق الزيارة كاملة من 15 إلى 30 دقيقة.' },
        q3: { q: 'ما هي الشروط الأساسية للتبرع بالدم؟', a: 'أن يكون العمر بين 18 و 65 عاماً، الوزن فوق 50 كجم، وأن تكون نسبة الهيموجلوبين في الدم ضمن المعدل الطبيعي.' },
        q4: { q: 'ماذا أفعل بعد التبرع بالدم؟', a: 'يُنصح بالاستراحة لمدة 10-15 دقيقة، وتناول وجبة خفيفة ومشروبات، وتجنب المجهود البدني الشديد لبقية اليوم.' }
      },
      facilities: {
        f1: {
          name: 'مجمع الشفاء الطبي',
          type: 'مستشفى حكومي',
          rec: 'في قسم بنك الدم بمجمع الشفاء (زمن {bloodType} يتوفر {count} وحدات من فصيلة .الوصول التقديري: {eta} دقائق)'
        },
        f2: {
          name: 'بنك الدم المركزي - غزة',
          type: 'بنك دم مركزي',
          rec: 'زمن) {bloodType} بنك الدم المركزي يحتوي على {count} وحدة متوفرة من فصيلة .(الوصول التقديري: {eta} دقائق'
        },
        f3: {
          name: 'مستشفى القدس الطبي',
          type: 'مستشفى أهلي',
          rec: 'زمن) {bloodType} مستشفى القدس يضم {count} وحدات جاهزة للتبرع من فصيلة .(الوصول التقديري: {eta} دقيقة'
        }
      }
    },
    footer: {
      desc: 'منصة مسعف لربط المتبرعين بالدم بالمحتاجين والمستشفيات لإنقاذ الأرواح بسرعة وكفاءة.',
      quickLinks: 'روابط سريعة',
      terms: 'الشروط والأحكام',
      termsDesc: 'اطّلع على الشروط والأحكام الخاصة باستكشاف منصتنا.',
      privacy: 'سياسة الخصوصية',
      privacyDesc: 'نحن نلتزم بحماية بياناتك وشخصيتك بأعلى معايير الأمان.',
      readMore: 'اقرأ المزيد',
      followUs: 'تابعنا',
      rights: 'جميع الحقوق محفوظة © منصة مسعف'
    }
  },
  en: {
    navbar: {
      announcement: '3,580 patients have been saved thanks to donors.',
      home: 'Home',
      about: 'About Us',
      features: 'Features',
      reviews: 'Reviews',
      partners: 'In Collaboration With',
      bloodGuide: 'Blood Donation Guide',
      medicalTips: 'Medical Tips',
      faq: 'FAQ',
      login: 'Login',
      register: 'Register',
      donateNow: 'Donate Now'
    },
    hero: {
      smallTitle: 'Musaef...',
      title: 'The Smart Platform for Emergency Blood Donation',
      titleLine1: 'Every Drop of Blood',
      titleLine2: 'Makes a Difference',
      titleHtml: 'The Smart Platform for Emergency<br>Blood Donation',
      desc: 'We connect blood donors with hospitals and patients quickly and intelligently to ensure blood arrives in time.',
      description: 'We connect blood donors with hospitals and patients quickly and intelligently to ensure blood arrives in time.',
      explore: 'Explore Platform',
      donate: 'Donate Now',
      donateNow: 'Donate Now',
      learnMore: 'Learn More'
    },
    home: {
      howItWorksTitle: 'How It Works?!',
      step1Title: 'Register on Platform',
      step1Desc: 'Create a donor account and set your location.',
      step2Title: 'Complete Health Profile',
      step2Desc: 'Enter your health data securely and confidentially.',
      step3Title: 'Receive Donation Requests',
      step3Desc: 'Receive nearby requests matching your blood type.',
      step4Title: 'Smart Matching',
      step4Desc: 'An intelligent system that matches donors with recipients.',
      step5Title: 'Head to Donation Center',
      step5Desc: 'Proceed to the assigned center to donate and save a life.',
      statSupported: 'Supported Cases',
      statRequests: 'Donation Requests',
      statHospitals: 'Hospitals Count',
      statDonors: 'Registered Donors',
      urgentTitle: 'Latest Emergency Cases',
      viewAll: 'View All Cases',
      veryCritical: 'Very Critical',
      urgent: 'Urgent',
      noCases: 'No urgent cases available at the moment.',
      requiredUnits: 'Required: {count} blood units',
      donate: 'Donate Now',
      share: 'Share Case',
      shareSuccess: 'Page link copied to clipboard!'
    },
    about: {
      title: 'About Us...',
      desc: 'A smart platform aiming to facilitate blood donation and save lives using artificial intelligence and modern technology. We connect donors and recipients in emergency moments.',
      descriptionHtml: 'A smart platform aiming to facilitate blood donation and save lives using artificial intelligence and modern technologies. We connect donors and recipients in emergency moments to ensure blood arrives in time.',
      visionTitle: 'Our Vision',
      visionDesc: 'To be the leading and most trusted blood donation platform across the Arab world.',
      missionTitle: 'Our Mission',
      missionDesc: 'Providing blood in the fastest time with the highest safety standards through a reliable technology-driven platform based on human cooperation.',
      goalsTitle: 'Our Goals',
      goalsDesc: 'Saving lives by enabling fast, safe, and effective blood donation utilizing AI and modern tech.',
      featuresTitle: 'Platform Features?!',
      reviewsTitle: 'Reviews & Feedback',
      partnersTitle: 'In Collaboration With',
      defaultLocation: 'Gaza - Palestine',
      loadingPartners: 'Loading partners...',
      features: {
        predict: { title: 'Smart Prediction', desc: 'Algorithms predicting future blood demand.' },
        inventory: { title: 'Inventory Management', desc: 'Real-time and continuous tracking of blood stocks.' },
        rewards: { title: 'Rewards System', desc: 'Incentive points and rewards for regular donors.' },
        alerts: { title: 'Urgent Alerts', desc: 'Instant notifications for emergency cases near you.' },
        maps: { title: 'Interactive Maps', desc: 'Accurately locating hospitals and donation centers.' },
        matching: { title: 'Instant Matching', desc: 'Connecting the right donor with the patient in record time.' }
      },
      reviews: {
        r1: { name: 'Ahmed Mahmoud', role: 'Regular Donor', text: 'The platform made donation seamless and connected me with urgent cases in record time. Excellent and human experience.' },
        r2: { name: 'Maryam Saeed', role: 'Beneficiary', text: 'Thanks to God and the platform, we obtained the required blood units for my father as quickly as possible.' },
        r3: { name: 'Al-Shifa Medical Complex', role: 'Medical Partner', text: 'An accurate and effective system that directly contributes to supporting the blood bank and covering emergency needs.' }
      },
      hospitals: {
        h1: { name: 'Al-Shifa Medical Complex', address: 'Gaza - Rimal' },
        h2: { name: 'Central Blood Bank Association', address: 'Gaza - Al-Wehda Street' },
        h3: { name: 'Central Blood Bank - Ministry of Health', address: 'Gaza - Al-Nasr' },
        h4: { name: 'Al-Ahli Arab Hospital (Al-Mamadani)', address: 'Gaza - Al-Zaytoun' },
        h5: { name: 'Al-Quds Hospital - Red Crescent', address: 'Gaza - Tel Al-Hawa' },
        h6: { name: 'Kamal Adwan Hospital', address: 'North Gaza - Beit Lahia' },
        h7: { name: 'Indonesian Hospital', address: 'North Gaza - Beit Lahia' },
        h8: { name: 'Nasser Medical Complex', address: 'Khan Younis - City Center' },
        h9: { name: 'Abu Yousuf Al-Najjar Hospital', address: 'Rafah - Al-Geneina' }
      }
    },
    guide: {
      mainTitle: 'Donation Guide',
      subTitle: '& Medical Guidelines',
      heroTitle: 'Donation Guide',
      heroSubtitle: '& Medical Guidelines',
      heroDesc: 'A concise guide to understanding blood group compatibility, safe donation conditions, and essential medical guidelines to ensure a safe donation and save lives.',
      desc: 'A concise guide to understanding blood group compatibility, safe donation conditions, and basic medical tips.',
      compTableTitle: 'Blood Group Compatibility Chart',
      compatibilityTitle: 'Blood Group Compatibility Chart',
      donorToWhom: 'Donation Compatibility (Who can donate to whom?)',
      donorToWhomDesc: 'Shows who can donate to each blood group based on compatibility.',
      donateCompatTitle: 'Donation Compatibility (Who can donate to whom?)',
      donateCompatDesc: 'Shows who can donate to each blood group based on compatibility.',
      receiverFromWhom: 'Receiving Compatibility (Who can receive blood?)',
      receiverFromWhomDesc: 'Shows who can safely receive blood from each group.',
      receiveCompatTitle: 'Receiving Compatibility (Who can receive blood?)',
      receiveCompatDesc: 'Shows who can safely receive blood from each group.',
      compatible: 'Compatible',
      compatibleSub: 'Can donate',
      incompatible: 'Incompatible',
      incompatibleSub: 'Cannot donate',
      aiSearchTitle: 'Smart Search for Donation Centers',
      aiSearchDesc: 'Select blood type to search for currently available nearby centers and hospitals.',
      searchBtn: 'Search',
      searching: 'Searching for nearby centers...',
      etaUnit: 'min',
      available: 'Available',
      unitsUnit: 'units',
      kmUnit: 'km',
      aiPromptPlaceholder: 'Select blood type and click search to view available nearby donation centers.',
      tipsTitle: 'Tips & Guidelines Section',
      tipsSectionTitle: 'Tips & Guidelines Section',
      ageTitle: 'Eligible Age',
      ageDesc: 'You must be between 18 and 65 years old to donate blood.',
      weightTitle: 'Eligible Weight',
      weightDesc: 'Your weight must be at least 50 kg to donate blood.',
      intervalTitle: 'When can you donate again?',
      intervalDesc: 'Men can donate every 8 weeks (56 days), and women every 12 weeks (84 days).',
      tips: {
        age: { title: 'Eligible Age', desc: 'You must be between 18 and 65 years old to donate blood.' },
        weight: { title: 'Eligible Weight', desc: 'Your weight must be at least 50 kg to donate blood.' },
        frequency: { title: 'When can you donate again?', desc: 'Men can donate every 8 weeks (56 days), and women every 12 weeks (84 days).' }
      },
      faqTitle: 'Frequently Asked Questions',
      faqSearchPlaceholder: 'Search for a question...',
      contactSupport: "If you didn't find the answer, contact us and our team will respond.",
      contactHeading: 'Contact Technical Support',
      contactSubheading: "If you didn't find the answer, contact us and our team will respond to you.",
      contactForm: {
        name: 'Full Name',
        email: 'Email Address',
        subject: 'Subject',
        message: 'Write your message here...',
        send: 'Send Message',
        sending: 'Sending...',
        successMsg: 'Your message has been sent successfully! We will reply soon.',
        errorMsg: 'An error occurred while sending the message. Please try again later.'
      },
      faqs: {
        q1: { q: 'Is blood donation safe?', a: 'Yes, blood donation is completely safe as sterile single-use equipment is used for each donor.' },
        q2: { q: 'How long does blood donation take?', a: 'The actual donation takes only 8 to 10 minutes, while the entire visit takes 15 to 30 minutes.' },
        q3: { q: 'What are the basic conditions for blood donation?', a: 'Being between 18 and 65 years old, weight above 50 kg, and hemoglobin within normal ranges.' },
        q4: { q: 'What should I do after donating blood?', a: 'It is recommended to rest for 10-15 minutes, consume light snacks and fluids, and avoid strenuous activity for the rest of the day.' }
      },
      facilities: {
        f1: {
          name: 'Al-Shifa Medical Complex',
          type: 'Government Hospital',
          rec: 'In the Blood Bank department at Al-Shifa Medical Complex, {count} units of {bloodType} are available. (Estimated arrival: {eta} mins)'
        },
        f2: {
          name: 'Central Blood Bank - Gaza',
          type: 'Central Blood Bank',
          rec: 'Central Blood Bank contains {count} available units of {bloodType}. (Estimated arrival: {eta} mins)'
        },
        f3: {
          name: 'Al-Quds Medical Hospital',
          type: 'Private Hospital',
          rec: 'Al-Quds Hospital includes {count} units ready for donation of {bloodType}. (Estimated arrival: {eta} mins)'
        }
      }
    },
    footer: {
      desc: 'Smart platform connecting blood donors with those in need during emergencies.',
      quickLinks: 'Quick Links',
      terms: 'Terms of Use',
      termsDesc: 'Agreement with usage policy',
      privacy: 'Privacy Policy',
      privacyDesc: 'We respect your personal data',
      readMore: 'Read More',
      followUs: 'Follow Us',
      rights: 'All rights reserved © Musaef Platform'
    }
  }
};

const savedLang = localStorage.getItem('musaef_lang') || 'ar';
if (typeof document !== 'undefined') {
  document.documentElement.setAttribute('dir', savedLang === 'ar' ? 'rtl' : 'ltr');
  document.documentElement.setAttribute('lang', savedLang);
}

export const i18n = createI18n({
  legacy: false,
  locale: savedLang,
  fallbackLocale: 'ar',
  messages
});

export default i18n;
