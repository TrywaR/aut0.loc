<?php

/**
 * Users
 */

 /**
 * @OA\Get(
 *   path="/core/api/",
 *   summary="Show users",
 *   operationId="GetUsers",
 *   @OA\RequestBody(
 *       required=true,
 *       @OA\MediaType(
 *           mediaType="application/json",
 *           @OA\Schema(
 *               type="object",
 *               @OA\Property(
 *                   property="table",
 *                   description="Table name in mySQL ",
 *                   type="string",
 *                   example="users"
 *               ),
 *               @OA\Property(
 *                   property="action",
 *                   description="Event",
 *                   type="string",
 *                   example="show"
 *               ),
 *               @OA\Property(
 *                   property="id",
 *                   description="User id if need singl result",
 *                   type="integer",
 *                   example="2"
 *               ),
 *           )
 *       )
 *   ),
 *  @OA\Response(
 *         response="200",
 *         description="ok",
 *         content={
 *             @OA\MediaType(
 *                 mediaType="application/json",
 *                 @OA\Schema(
 *                     @OA\Property(
 *                         property="id",
 *                         type="integer",
 *                     ),
 *                     @OA\Property(
 *                         property="name",
 *                         type="string",
 *                     ),
 *                     @OA\Property(
 *                         property="email",
 *                         type="string",
 *                     ),
 *                     @OA\Property(
 *                         property="password",
 *                         type="string",
 *                     ),
 *                     @OA\Property(
 *                         property="role",
 *                         type="integer",
 *                     ),
 *                     example={
 *                         "data": {
 *                              "id": "2",
 *                              "name": "User",
 *                              "email": "user@email.com",
 *                              "password": "****",
 *                              "role": "1",
 *                          }
 *                     }
 *                 )
 *             )
 *         }
 *     ),
 *   @OA\Response(response="404",description="Elems not found"),
 * )
 */

 /**
 * @OA\Post(
 *   path="/core/api/",
 *   summary="Add user",
 *   operationId="PostUsers",
 *   @OA\RequestBody(
 *       required=true,
 *       @OA\MediaType(
 *           mediaType="application/json",
 *           @OA\Schema(
 *               type="object",
 *               @OA\Property(
 *                   property="table",
 *                   description="Table name in mySQL ",
 *                   type="string",
 *                   example="users"
 *               ),
 *               @OA\Property(
 *                   property="action",
 *                   description="Event",
 *                   type="string",
 *                   example="show"
 *               ),
 *               @OA\Property(
 *                   property="id",
 *                   description="User id",
 *                   type="integer",
 *                   example="2"
 *               ),
 *               @OA\Property(
 *                   property="name",
 *                   description="User name",
 *                   type="string",
 *                   example="User"
 *               ),
 *               @OA\Property(
 *                   property="email",
 *                   description="User email",
 *                   type="string",
 *                   example="user@email.com"
 *               ),
 *               @OA\Property(
 *                   property="role",
 *                   description="User role, 0 - default, 1 - manager, 2 - admin",
 *                   type="integer",
 *                   example="0"
 *               ),
 *           )
 *       )
 *   ),
 *  @OA\Response(
 *         response="200",
 *         description="ok",
 *         content={
 *             @OA\MediaType(
 *                 mediaType="application/json",
 *                 @OA\Schema(
 *                     @OA\Property(
 *                         property="message",
 *                         type="string",
 *                     ),
 *                     example={
 *                         "message": "Success post!",
 *                     }
 *                 )
 *             )
 *         }
 *     ),
 * )
 */

class user extends model
{
    public $table_name = 'users';

    /**
     * Summary of id
     * @var int
     */
    public int $id = '';
    /**
     * Summary of name
     * @var string
     */
    public string $name = '';
    /**
     * Summary of email
     * @var string
     */
    public string $email = '';
    /**
     * Summary of password
     * @var string
     */
    public string $password = '';
    /**
     * Summary of role
     * @var int
     */
    public int $role = '';

    function check_email ( $sEmail = '' ){
        $oUser = new user();
        $oUser->query .= ' AND `email` = "' . $sEmail . '"';
        if ( count($oUser->get()) ) return true;
        else return false;
    }
    
    function __construct(){
    }
}
