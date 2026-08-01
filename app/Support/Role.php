<?php

namespace App\Support;

/**
 * أدوار النظام الخمسة، مطابقة تمامًا لمصفوفة الصلاحيات في وثيقة
 * 11-Security-Infrastructure.docx (البند BR-SEC-01: فصل واجبات صارم).
 */
class Role
{
    public const ADMIN = 'admin';
    public const NURSERY_MANAGER = 'nursery_manager';
    public const INVENTORY_KEEPER = 'inventory_keeper';
    public const SALES_REP = 'sales_rep';
    public const ACCOUNTANT = 'accountant';

    public const ALL = [
        self::ADMIN, self::NURSERY_MANAGER, self::INVENTORY_KEEPER,
        self::SALES_REP, self::ACCOUNTANT,
    ];

    /**
     * أدوار مخوَّلة بتعديل الأصناف وقوائم الأسعار (BR-SEC-02: مركزي ومقيد).
     */
    public const CAN_MANAGE_PRICING = [self::ADMIN];

    /**
     * أدوار مخوَّلة بحركات المخزون اليومية (استلام/تحويل/جرد/تغيير حالة).
     */
    public const CAN_MANAGE_INVENTORY = [self::ADMIN, self::NURSERY_MANAGER, self::INVENTORY_KEEPER];

    /**
     * أدوار مخوَّلة بإنشاء عروض أسعار وتأكيدها كأوامر بيع.
     */
    public const CAN_MANAGE_SALES = [self::ADMIN, self::SALES_REP];

    /**
     * أدوار مخوَّلة بتسجيل تحصيل الدفعات (BR-SEC-01: المبيعات لا تعدل التحصيل المالي).
     */
    public const CAN_MANAGE_PAYMENTS = [self::ADMIN, self::ACCOUNTANT];

    /**
     * أدوار مخوَّلة باعتماد قرارات استراتيجية (عقود B2B، قرار الدخول في منافسة B2G).
     */
    public const CAN_APPROVE_STRATEGIC = [self::ADMIN];

    /**
     * أدوار مخوَّلة بتأكيد التسليم الفعلي وخصم المخزون النهائي.
     */
    public const CAN_CONFIRM_DELIVERY = [self::ADMIN, self::NURSERY_MANAGER, self::INVENTORY_KEEPER];
}
