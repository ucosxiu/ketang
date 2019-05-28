<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-3-7
 * Time: 1:00
 */
namespace app\common\model;


class Leave extends Common
{
    protected $name = 'leave';
    protected $pk = 'leave_id';
    protected $resultSetType = 'collection';

    protected $autoWriteTimestamp = 'add_time';
    protected $createTime = 'add_time';

    protected $auto = [];


    /**
     * 日志记录
     */
    function logs() {
        return $this->hasMany('leavelog', 'leave_id', 'leave_id');
    }

    function tasks() {
        return $this->hasMany('leavetask', 'leave_id', 'leave_id');
    }

    function student() {
        return $this->belongsTo('student', 'student_id', 'student_id')->with(['classes']);
    }

    function college()
    {
        return $this->belongsTo('college', 'college_id', 'college_id');
    }

    /**
     * 申请
     * @param $param
     */
    function apply($param) {
        $student_model = model('student');
        $student = $student_model->with('classes')->get($param['student_id']);
        $teacher_id = $student->classes->teacher_id;
        if (!$teacher_id) {
            return false;
        }
        $param['college_id'] = $student['college_id'];

        if ($this->data($param)->allowField(true)->save()) {
            //申请成功添加通知
            $leave_id = $this->leave_id;

            //获取学生班级
            $leavel_task_model = model('leavetask');
            $data = [];
            $data['leave_id'] = $leave_id;
            $data['step'] = 1;
            $data['teacher_id'] = $teacher_id;
            $leavel_task_model->data($data)->save();

            //发送微信通知
            $fans = model('fans')->get(['uid'=>$teacher_id, 'utype' => 1]);
            $leave = model('leave')->get($leave_id);
            $types = ['事假', '病假', '丧假', '其他'];
            if ($fans) {
                $openid = $fans['openid'];
                sendTemplateFromId($openid, '', [
                    'keyword1' => $student->student_name,
                    'keyword2' => $leave->add_time,
                    'keyword3' => $types[$leave->leave_type],
                    'keyword4' => $leave->mask,
                ]);
            }

            return $leave_id;
        }

        return false;
    }

    function editLeave($param) {
        $student_model = model('student');
        $student = $student_model->with('classes')->get($param['student_id']);
        $teacher_id = $student->classes->teacher_id;
        if (!$teacher_id) {
            return false;
        }
        $param['college_id'] = $student['college_id'];

        if ($this->data($param)->allowField(true)->save()) {
            //修改成功添加通知
            $leave_id = $this->leave_id;

            //获取学生班级
            $leavel_task_model = model('leavetask');
            //先删除所有相关通知
            $leavel_task_model->where(['leave_id'=>$this->leave_id])->delete();


            $data = [];
            $data['leave_id'] = $leave_id;
            $data['step'] = 1;
            $data['teacher_id'] = $teacher_id;
            $leavel_task_model->data($data)->save();
            return true;
        }

        return false;
    }

    /**
     * @param $leave_id
     * @param $teacher_id
     * @return bool
     */
    function agree($leave_id, $teacher_id) {
        $teacher_model = model('teacher');
        $teacher = $teacher_model->get($teacher_id);
        $leavel_task_model = model('leavetask');
        $task = $leavel_task_model->get(['leave_id' => $leave_id, 'teacher_id' => $teacher_id]);

        if (!$task) {
            return false;
        }

        if ($task['status'] != 0) {
            return false;
        }

        $leave = $this->with('college')->get($leave_id);
        if (!$leave) {
            return false;
        }

        if ($leave['islong'] == 1) {
            //出校
            $step = $task->step;
            switch ($step) {
                case 1:
                    //获取院长 发送
                    $dean_id = $leave->college->dean_id;
                    if (!$dean_id) {
                        return false;
                    }
                    //出校 老师同意
                    $leave->isagree1 = 1;
                    $leave->save();


                    //推送给院长
                    $data = [];
                    $data['leave_id'] = $leave_id;
                    $data['step'] = 2;
                    $data['teacher_id'] = $dean_id;
                    $leavel_task_model->data($data)->save();

                    //添加日志
                    $leave_log_model = model('leavelog');
                    $log = [
                        'leave_id'  => $leave_id,
                        'teacher_id'    => $teacher_id,
                        'note'    => '老师:'.$teacher->teacher_name . ' 同意了你的请假申请!'
                    ];
                    $leave_log_model->addLog($log);
                    break;
                case 2:
                    //获取 学生会
                    $union = $teacher_model->get(['position'=>2]);
                    if (!$union) {
                        return false;
                    }

                    //出校 院长同意
                    $leave->isagree2 = 1;
                    $leave->save();

                    //推送给学生会
                    $data = [];
                    $data['leave_id'] = $leave_id;
                    $data['step'] = 3;
                    $data['teacher_id'] = $union['teacher_id'];
                    $leavel_task_model->data($data)->save();

                    //添加日志
                    $leave_log_model = model('leavelog');
                    $log = [
                        'leave_id'  => $leave_id,
                        'teacher_id'    => $teacher_id,
                        'note'    => '院长:'.$teacher->teacher_name . ' 同意了你的请假申请!'
                    ];
                    $leave_log_model->addLog($log);
                    break;
                case 3:
                    //出校 学生会同意 完成
                    $leave->isagree3 = 1;
                    $leave->status = 1;
                    $leave->save();

                    //添加日志
                    $leave_log_model = model('leavelog');
                    $log = [
                        'leave_id'  => $leave_id,
                        'teacher_id'    => $teacher_id,
                        'note'    => '学生会:'.$teacher->teacher_name . ' 同意了你的请假申请!'
                    ];
                    $leave_log_model->addLog($log);
                    break;
            }

            //同意审核
            $task->audit_time =  time();
            $task->status = 1;
            $task->save();

        } else {
            //不出校 同意 直接完成
            $task->audit_time =  time();
            $task->status = 1;
            $task->save();

            $leave->isagree1 = 1;
            $leave->status = 1;
            $leave->save();

            //添加日志
            $leave_log_model = model('leavelog');

            $log = [
                'leave_id'  => $leave_id,
                'teacher_id'    => $teacher_id,
                'note'    => '老师:'.$teacher->teacher_name . ' 同意了你的请假申请!'
            ];
            $leave_log_model->addLog($log);
        }

        return true;
    }


    /**
     * 拒绝申请
     * @param $leave_id
     * @param $teacher_id
     * @param $reason
     */
    function refuse($leave_id, $teacher_id, $reason) {
        $teacher_model = model('teacher');
        $teacher = $teacher_model->get($teacher_id);
        $leavel_task_model = model('leavetask');
        $task = $leavel_task_model->get(['leave_id' => $leave_id, 'teacher_id' => $teacher_id]);

        if (!$task) {
            return false;
        }

        if ($task['status'] != 0) {
            return false;
        }

        $leave = $this->with('college')->get($leave_id);
        if (!$leave) {
            return false;
        }

        //添加日志
        $leave_log_model = model('leavelog');

        if ($leave['islong'] == 1) {
            $step = $task->step;
            switch ($step) {
                case 1:
                    $leave->isagree1 = 2; //拒绝

                    $log = [
                        'leave_id'  => $leave_id,
                        'teacher_id'    => $teacher_id,
                        'note'    => '老师:'.$teacher->teacher_name . ' 拒绝了你的请假申请!'
                    ];
                    $leave_log_model->addLog($log);
                    break;
                case 2:
                    $leave->isagree2 = 2; //拒绝

                    $log = [
                        'leave_id'  => $leave_id,
                        'teacher_id'    => $teacher_id,
                        'note'    => '院长:'.$teacher->teacher_name . ' 拒绝了你的请假申请!'
                    ];
                    $leave_log_model->addLog($log);
                    break;
                case 3:
                    $leave->isagree3 = 2; //拒绝


                    $log = [
                        'leave_id'  => $leave_id,
                        'teacher_id'    => $teacher_id,
                        'note'    => '学生会:'.$teacher->teacher_name . ' 拒绝了你的请假申请!'
                    ];
                    $leave_log_model->addLog($log);
                    break;
            }
        } else {
            $leave->isagree1 = 2; //拒绝

            $log = [
                'leave_id'  => $leave_id,
                'teacher_id'    => $teacher_id,
                'note'    => '老师:'.$teacher->teacher_name . ' 拒绝了你的请假申请!'
            ];
            $leave_log_model->addLog($log);
        }

        $leave->status = 2; //拒绝
        $leave->reason = $reason; //拒绝
        $leave->save();


        //拒绝
        $task->audit_time =  time();
        $task->status = 2;
        $task->save();
        return true;
    }

    /**
     * @param $leave_id
     * @param $teacher_id
     * @param $reason
     */
    function cancel($leave_id, $teacher_id, $reason) {
        $teacher_model = model('teacher');
        $teacher = $teacher_model->get($teacher_id);
        $leavel_task_model = model('leavetask');
        $task = $leavel_task_model->get(['leave_id' => $leave_id, 'teacher_id' => $teacher_id]);

        if (!$task) {
            return false;
        }


        $leave = $this->with('college')->get($leave_id);
        if (!$leave) {
            return false;
        }

        //添加日志
        $leave_log_model = model('leavelog');
        if ($leave['islong'] == 1) {
            $step = $task->step;
            switch ($step) {
                case 1:
                    $leave->isagree1 = 2; //拒绝

                    $log = [
                        'leave_id'  => $leave_id,
                        'teacher_id'    => $teacher_id,
                        'note'    => '老师:'.$teacher->teacher_name . ' 强制取消了你的请假申请!'
                    ];
                    $leave_log_model->addLog($log);
                    break;
                case 2:
                    $leave->isagree2 = 2; //拒绝

                    $log = [
                        'leave_id'  => $leave_id,
                        'teacher_id'    => $teacher_id,
                        'note'    => '院长:'.$teacher->teacher_name . ' 强制取消了你的请假申请!'
                    ];
                    $leave_log_model->addLog($log);
                    break;
                case 3:
                    $leave->isagree3 = 2; //拒绝

                    $log = [
                        'leave_id'  => $leave_id,
                        'teacher_id'    => $teacher_id,
                        'note'    => '学生会:'.$teacher->teacher_name . ' 强制取消了你的请假申请!'
                    ];
                    $leave_log_model->addLog($log);
                    break;
            }
        } else {
            $leave->isagree1 = 2; //拒绝

            $log = [
                'leave_id'  => $leave_id,
                'teacher_id'    => $teacher_id,
                'note'    => '老师:'.$teacher->teacher_name . ' 强制取消了你的请假申请!'
            ];
            $leave_log_model->addLog($log);
        }

        $task->status = 2;
        $task->reason = $reason;
        $task->audit_time =  time();
        $task->save();

        $leave->status = 2; //拒绝
        $leave->reason = $reason; //拒绝
        $leave->save();
        return true;

    }


    /**
     * 获取老师的请假列表
     * @param $teacher_id
     */
    function getListByTeacher($condition = [], $field = '*', $order = 'leave_id desc', $extend = array(), $master = false) {
        $op = $this->alias('a')->rightJoin('leave_task b','b.leave_id = a.leave_id')->where($condition);
        $op = isset($extend['with']) ? $op->with($extend['with']) : $op;
        return $op->order($order)->field($field)->paginate(null, false, ['page' => $_POST['page']]);
    }


    /**
     * 获取学生的请假列表
     * @param $teacher_id
     */
    function getListByStudent($condition = [], $field = '*', $order = 'leave_id desc', $extend = array(), $master = false) {
        $op = $this->where($condition);
        $op = isset($extend['with']) ? $op->with($extend['with']) : $op;
        return $op->order($order)->field($field)->paginate(null, false, ['page' => $_POST['page']]);
    }
}
