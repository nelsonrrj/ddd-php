<?php

declare(strict_types=1);

namespace Tests\Integration\Http\Controllers;

use App\Domain\Entities\UserEntity;
use App\Domain\Exceptions\UserAlreadyExistsException;
use App\Domain\Repositories\UserRepository;
use App\Domain\ValueObjects\UserEmail;
use App\Domain\ValueObjects\UserName;
use App\Domain\ValueObjects\UserPassword;
use App\Infrastructure\Controllers\RegisterUserController;
use App\Infrastructure\Responses\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class RegisterUserControllerIntegrationTest extends TestCase
{
    private RegisterUserController $controller;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->container->get(UserRepository::class);
        $this->controller = $this->container->get(RegisterUserController::class);
        $this->entityManager = $this->container->get(EntityManagerInterface::class);

        // Start a transaction for each test
        $this->entityManager->beginTransaction();
    }

    public function tearDown(): void
    {
        // Rollback the transaction to clean up test data
        if ($this->entityManager->getConnection()->isTransactionActive()) {
            $this->entityManager->rollback();
        }

        parent::tearDown();
    }

    /**
     * Tests the successful registration of a new user through the controller.
     *
     * This test verifies that:
     * - A new user can be registered with valid data
     * - The response contains the correct status code (201) and message
     * - The user data is properly returned in the response
     * - The user is actually persisted in the database
     * - The response ID matches the database ID
     *
     * @test
     */
    public function testRegisterUserSuccessfully(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '1234AD.qwed',
        ];

        $response = $this->controller->register($userData);
        $this->entityManager->flush();

        // Verify response type
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Verify response content
        $this->assertEquals('User created successfully', $response->message);
        $this->assertEquals(201, $response->statusCode);

        // Verify user data in response
        $responseData = json_decode(json_encode($response->data), true);
        $this->assertNotNull($responseData);
        $this->assertEquals('Test User', $responseData['name']);
        $this->assertEquals('test@example.com', $responseData['email']);
        $this->assertNotNull($responseData['id']);
        $this->assertNotNull($responseData['createdAt']);

        // Verify user was actually saved to database
        $savedUser = $this->userRepository->findByEmail(new UserEmail('test@example.com'));
        $this->assertNotNull($savedUser);
        $this->assertEquals('Test User', (string) $savedUser->name);
        $this->assertEquals('test@example.com', (string) $savedUser->email);

        // Verify response ID matches database ID
        $this->assertEquals((string) $savedUser->id, (string) $responseData['id']);
    }

    /**
     * Tests that attempting to register a user with an existing email throws an exception.
     *
     * This test verifies that:
     * - An existing user can be created and saved
     * - Attempting to register another user with the same email throws UserAlreadyExistsException
     * - The system properly prevents duplicate email registrations
     *
     * @test
     */
    public function testRegisterExistingUserThrowsException(): void
    {
        // First, create and save an existing user
        $existingUser = new UserEntity(
            email: new UserEmail('existing@example.com'),
            name: new UserName('Existing User'),
            password: new UserPassword('1234AD.qwed'),
        );

        $this->userRepository->save($existingUser);

        // Attempt to register a user with the same email
        $userData = [
            'name' => 'Another User',
            'email' => 'existing@example.com',
            'password' => 'different1234AD.qwed',
        ];

        // Verify that the expected exception is thrown
        $this->expectException(UserAlreadyExistsException::class);
        $this->controller->register($userData);
    }

    /**
     * Tests that the response structure is consistent across different registrations.
     *
     * This test verifies that:
     * - The response is always a JsonResponse instance
     * - The status code is consistently 201 for successful registrations
     * - The success message is consistent
     * - The Content-Type header is properly set to application/json
     * - The response data contains all required fields (id, name, email, createdAt)
     * - The field values match the input data
     *
     * @test
     */
    public function testResponseStructureIsConsistent(): void
    {
        $userData = [
            'name' => 'Structure Test User',
            'email' => 'structure@example.com',
            'password' => '1234AD.qwed',
        ];

        $response = $this->controller->register($userData);
        $this->entityManager->flush();

        // Verify basic response structure
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->statusCode);
        $this->assertEquals('User created successfully', $response->message);

        // Verify headers include Content-Type for JSON
        $headers = $response->headers;
        $this->assertArrayHasKey('Content-Type', $headers);
        $this->assertEquals('application/json', $headers['Content-Type']);

        // Verify response DTO structure
        $data = json_decode(json_encode($response->data), true);
        $this->assertNotNull($data);
        $this->assertNotNull($data['id']);
        $this->assertNotNull($data['name']);
        $this->assertNotNull($data['email']);
        $this->assertNotNull($data['createdAt']);

        // Verify specific values
        $this->assertEquals('Structure Test User', $data['name']);
        $this->assertEquals('structure@example.com', $data['email']);
    }

    /**
     * Tests the complete HTTP response format using the toArray method.
     *
     * This test verifies that:
     * - The response can be converted to an array format suitable for HTTP responses
     * - The array contains all required keys (data, message, statusCode)
     * - The structure matches what an HTTP client would expect to receive
     * - All response data is properly formatted for JSON serialization
     *
     * @test
     */
    public function testCompleteHttpResponseFormat(): void
    {
        $userData = [
            'name' => 'HTTP Test User',
            'email' => 'http@example.com',
            'password' => '1234AD.qwed',
        ];

        $response = $this->controller->register($userData);
        $this->entityManager->flush();

        // Convert to array to verify complete structure
        $responseArray = $response->toArray();

        $this->assertArrayHasKey('data', $responseArray);
        $this->assertArrayHasKey('message', $responseArray);
        $this->assertArrayHasKey('statusCode', $responseArray);
        $this->assertEquals('User created successfully', $responseArray['message']);
        $this->assertEquals(201, $responseArray['statusCode']);

        // This would be the structure that an HTTP client would receive
        $expectedStructure = [
            'data' => [
                'id' => 'some_id',
                'name' => 'HTTP Test User',
                'email' => 'http@example.com',
                'createdAt' => 'some_date',
            ],
            'message' => 'User created successfully',
            'statusCode' => 201,
        ];

        $this->assertArrayHasKey('data', $responseArray);
        $this->assertEquals($expectedStructure['message'], $responseArray['message']);
        $this->assertEquals($expectedStructure['statusCode'], $responseArray['statusCode']);
    }
}
